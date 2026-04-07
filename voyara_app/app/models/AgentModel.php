<?php
/**
 * Author:  Kiran Khadka
 * Version: 1.0.0 (First edition)
 * Contact: +977-9869756622
 * Mail:    therealkiranda@gmail.com
 * © 2026 Kiran Khadka. All rights reserved.
 */
class AgentModel {
    public static function findByEmail(string $email): ?array {
        return DB::fetchOne('SELECT * FROM agents WHERE email = ? AND is_active = 1', [$email]);
    }

    public static function findById(int $id): ?array {
        return DB::fetchOne('SELECT * FROM agents WHERE id = ?', [$id]);
    }

    public static function verifyPassword(string $plain, string $hash): bool {
        return password_verify($plain, $hash);
    }

    public static function all(): array {
        return DB::fetchAll('SELECT id, name, email, phone, is_active, last_login FROM agents ORDER BY name');
    }

    public static function create(array $d): int {
        DB::query(
            'INSERT INTO agents (name, email, phone, password, bio) VALUES (?,?,?,?,?)',
            [$d['name'], $d['email'], $d['phone'] ?? null,
             password_hash($d['password'], PASSWORD_BCRYPT, ['cost' => 12]),
             $d['bio'] ?? null]
        );
        return (int) DB::lastInsertId();
    }

    public static function bookingCount(int $agentId): int {
        return (int) DB::fetchColumn('SELECT COUNT(*) FROM bookings WHERE agent_id = ?', [$agentId]);
    }
}
