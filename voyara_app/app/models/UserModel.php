<?php
/**
 * Author:  Kiran Khadka
 * Version: 1.0.0 (First edition)
 * Contact: +977-9869756622
 * Mail:    therealkiranda@gmail.com
 * © 2026 Kiran Khadka. All rights reserved.
 */
class UserModel {
    public static function findByEmail(string $email): ?array {
        return DB::fetchOne('SELECT * FROM users WHERE email = ?', [$email]);
    }

    public static function findById(int $id): ?array {
        return DB::fetchOne('SELECT * FROM users WHERE id = ?', [$id]);
    }

    public static function create(array $data): int {
        DB::query(
            'INSERT INTO users (name, email, phone, password) VALUES (?, ?, ?, ?)',
            [
                $data['name'],
                $data['email'],
                $data['phone'] ?? null,
                password_hash($data['password'], PASSWORD_BCRYPT, ['cost' => 12]),
            ]
        );
        return (int) DB::lastInsertId();
    }

    public static function verifyPassword(string $plain, string $hash): bool {
        return password_verify($plain, $hash);
    }

    public static function update(int $id, array $data): void {
        $sets   = [];
        $params = [];
        $allowed = ['name', 'phone', 'avatar'];
        foreach ($allowed as $col) {
            if (array_key_exists($col, $data)) {
                $sets[]   = "{$col} = ?";
                $params[] = $data[$col];
            }
        }
        if (empty($sets)) return;
        $params[] = $id;
        DB::query('UPDATE users SET ' . implode(', ', $sets) . ' WHERE id = ?', $params);
    }

    public static function updatePassword(int $id, string $newPassword): void {
        DB::query(
            'UPDATE users SET password = ? WHERE id = ?',
            [password_hash($newPassword, PASSWORD_BCRYPT, ['cost' => 12]), $id]
        );
    }

    public static function emailExists(string $email, int $excludeId = 0): bool {
        $row = DB::fetchOne(
            'SELECT id FROM users WHERE email = ? AND id != ?',
            [$email, $excludeId]
        );
        return (bool) $row;
    }

    public static function all(int $limit = 50, int $offset = 0): array {
        return DB::fetchAll(
            'SELECT id, name, email, phone, is_active, created_at FROM users ORDER BY created_at DESC LIMIT ? OFFSET ?',
            [$limit, $offset]
        );
    }

    public static function count(): int {
        return (int) DB::fetchColumn('SELECT COUNT(*) FROM users');
    }

    public static function toggleActive(int $id): void {
        DB::query('UPDATE users SET is_active = NOT is_active WHERE id = ?', [$id]);
    }
}
