<?php
/**
 * Author:  Kiran Khadka
 * Version: 1.0.0 (First edition)
 * Contact: +977-9869756622
 * Mail:    therealkiranda@gmail.com
 * © 2026 Kiran Khadka. All rights reserved.
 */
class ReviewModel {
    public static function create(array $d): int {
        DB::query(
            'INSERT INTO reviews (user_id, package_id, booking_id, rating, title, body) VALUES (?,?,?,?,?,?)',
            [$d['user_id'], $d['package_id'], $d['booking_id'], $d['rating'], $d['title'] ?? null, $d['body']]
        );
        return (int) DB::lastInsertId();
    }

    public static function byPackage(int $packageId, int $limit = 10, int $offset = 0): array {
        return DB::fetchAll(
            "SELECT r.*, u.name AS user_name, u.avatar AS user_avatar
             FROM reviews r JOIN users u ON u.id = r.user_id
             WHERE r.package_id = ? AND r.status = 'approved'
             ORDER BY r.created_at DESC LIMIT ? OFFSET ?",
            [$packageId, $limit, $offset]
        );
    }

    public static function existsForBooking(int $bookingId): bool {
        return (bool) DB::fetchOne('SELECT id FROM reviews WHERE booking_id = ?', [$bookingId]);
    }

    public static function all(string $status = 'pending', int $limit = 30, int $offset = 0): array {
        return DB::fetchAll(
            "SELECT r.*, u.name AS user_name, p.title AS package_title
             FROM reviews r JOIN users u ON u.id = r.user_id JOIN packages p ON p.id = r.package_id
             WHERE r.status = ? ORDER BY r.created_at DESC LIMIT ? OFFSET ?",
            [$status, $limit, $offset]
        );
    }

    public static function updateStatus(int $id, string $status): void {
        DB::query('UPDATE reviews SET status = ? WHERE id = ?', [$status, $id]);
    }

    public static function countPending(): int {
        return (int) DB::fetchColumn("SELECT COUNT(*) FROM reviews WHERE status='pending'");
    }
}
