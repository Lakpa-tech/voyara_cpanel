<?php
/**
 * Author:  Kiran Khadka
 * Version: 1.0.0 (First edition)
 * Contact: +977-9869756622
 * Mail:    therealkiranda@gmail.com
 * © 2026 Kiran Khadka. All rights reserved.
 */
class BookingModel {
    public static function create(array $d): int {
        DB::beginTransaction();
        try {
            $ref = self::generateRef();
            DB::query(
                "INSERT INTO bookings (booking_ref, user_id, package_id, travel_date, persons, total_price, special_requests)
                 VALUES (?,?,?,?,?,?,?)",
                [$ref, $d['user_id'], $d['package_id'], $d['travel_date'],
                 $d['persons'], $d['total_price'], $d['special_requests'] ?? null]
            );
            $bookingId = (int) DB::lastInsertId();

            DB::query(
                "INSERT INTO payments (booking_id, amount, method) VALUES (?,?,?)",
                [$bookingId, $d['total_price'], 'bank_transfer']
            );

            DB::commit();
            return $bookingId;
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    private static function generateRef(): string {
        do {
            $ref = 'VYR-' . strtoupper(bin2hex(random_bytes(4)));
        } while (DB::fetchOne('SELECT id FROM bookings WHERE booking_ref = ?', [$ref]));
        return $ref;
    }

    public static function findById(int $id): ?array {
        return DB::fetchOne(
            "SELECT b.*, p.title AS package_title, p.cover_image, p.price AS package_price,
                    u.name AS user_name, u.email AS user_email, u.phone AS user_phone,
                    a.name AS agent_name,
                    pay.status AS payment_status, pay.transaction_ref, pay.receipt_path, pay.amount AS paid_amount
             FROM bookings b
             JOIN packages p ON p.id = b.package_id
             JOIN users    u ON u.id = b.user_id
             LEFT JOIN agents  a   ON a.id  = b.agent_id
             LEFT JOIN payments pay ON pay.booking_id = b.id
             WHERE b.id = ?",
            [$id]
        );
    }

    public static function findByRef(string $ref): ?array {
        return DB::fetchOne(
            "SELECT b.*, p.title AS package_title, p.cover_image,
                    u.name AS user_name,
                    pay.status AS payment_status, pay.transaction_ref, pay.receipt_path
             FROM bookings b
             JOIN packages  p   ON p.id  = b.package_id
             JOIN users     u   ON u.id  = b.user_id
             LEFT JOIN payments pay ON pay.booking_id = b.id
             WHERE b.booking_ref = ?",
            [$ref]
        );
    }

    public static function byUser(int $userId): array {
        return DB::fetchAll(
            "SELECT b.*, p.title AS package_title, p.cover_image,
                    pay.status AS payment_status
             FROM bookings b
             JOIN packages  p   ON p.id  = b.package_id
             LEFT JOIN payments pay ON pay.booking_id = b.id
             WHERE b.user_id = ?
             ORDER BY b.created_at DESC",
            [$userId]
        );
    }

    public static function all(array $filters = [], int $limit = 30, int $offset = 0): array {
        [$where, $params] = self::buildFilters($filters);
        $sql = "SELECT b.*, p.title AS package_title, u.name AS user_name,
                       pay.status AS payment_status
                FROM bookings b
                JOIN packages p ON p.id = b.package_id
                JOIN users    u ON u.id = b.user_id
                LEFT JOIN payments pay ON pay.booking_id = b.id
                WHERE 1=1 {$where}
                ORDER BY b.created_at DESC LIMIT ? OFFSET ?";
        $params[] = $limit;
        $params[] = $offset;
        return DB::fetchAll($sql, $params);
    }

    public static function countAll(array $filters = []): int {
        [$where, $params] = self::buildFilters($filters);
        return (int) DB::fetchColumn(
            "SELECT COUNT(*) FROM bookings b
             JOIN packages p ON p.id = b.package_id
             JOIN users u ON u.id = b.user_id
             WHERE 1=1 {$where}",
            $params
        );
    }

    private static function buildFilters(array $f): array {
        $where = ''; $params = [];
        if (!empty($f['status'])) { $where .= ' AND b.status = ?'; $params[] = $f['status']; }
        if (!empty($f['user_id'])) { $where .= ' AND b.user_id = ?'; $params[] = $f['user_id']; }
        if (!empty($f['agent_id'])) { $where .= ' AND b.agent_id = ?'; $params[] = $f['agent_id']; }
        if (!empty($f['keyword'])) {
            $where .= ' AND (b.booking_ref LIKE ? OR u.name LIKE ? OR p.title LIKE ?)';
            $kw = '%' . $f['keyword'] . '%';
            array_push($params, $kw, $kw, $kw);
        }
        return [$where, $params];
    }

    public static function updateStatus(int $id, string $status, ?string $notes = null): void {
        DB::query(
            'UPDATE bookings SET status = ?, admin_notes = COALESCE(?, admin_notes) WHERE id = ?',
            [$status, $notes, $id]
        );
    }

    public static function assignAgent(int $id, int $agentId): void {
        DB::query('UPDATE bookings SET agent_id = ? WHERE id = ?', [$agentId, $id]);
    }

    public static function uploadReceipt(int $bookingId, string $filename, string $txRef): void {
        DB::query(
            'UPDATE payments SET receipt_path = ?, transaction_ref = ?, status = ? WHERE booking_id = ?',
            [$filename, $txRef, 'pending', $bookingId]
        );
    }

    public static function verifyPayment(int $bookingId, int $adminId, string $status): void {
        DB::query(
            "UPDATE payments SET status = ?, verified_by = ?, verified_at = NOW() WHERE booking_id = ?",
            [$status, $adminId, $bookingId]
        );
        if ($status === 'verified') {
            DB::query("UPDATE bookings SET status = 'confirmed' WHERE id = ?", [$bookingId]);
        }
    }

    public static function byAgent(int $agentId, int $limit = 30, int $offset = 0): array {
        return DB::fetchAll(
            "SELECT b.*, p.title AS package_title, u.name AS user_name, u.email AS user_email,
                    pay.status AS payment_status
             FROM bookings b
             JOIN packages  p ON p.id = b.package_id
             JOIN users     u ON u.id = b.user_id
             LEFT JOIN payments pay ON pay.booking_id = b.id
             WHERE b.agent_id = ?
             ORDER BY b.created_at DESC LIMIT ? OFFSET ?",
            [$agentId, $limit, $offset]
        );
    }

    public static function stats(): array {
        return [
            'total'     => (int) DB::fetchColumn('SELECT COUNT(*) FROM bookings'),
            'pending'   => (int) DB::fetchColumn("SELECT COUNT(*) FROM bookings WHERE status='pending'"),
            'confirmed' => (int) DB::fetchColumn("SELECT COUNT(*) FROM bookings WHERE status='confirmed'"),
            'revenue'   => (float) DB::fetchColumn("SELECT COALESCE(SUM(amount),0) FROM payments WHERE status='verified'"),
        ];
    }
}
