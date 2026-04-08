<?php
/**
 * Author:  Kiran Khadka
 * Version: 1.0.0 (First edition)
 * Contact: +977-9869756622
 * Mail:    therealkiranda@gmail.com
 * © 2026 Kiran Khadka. All rights reserved.
 */
class AdminModel {
    public static function findByEmail(string $email): ?array {
        return DB::fetchOne('SELECT * FROM admins WHERE email = ? AND is_active = 1', [$email]);
    }

    public static function verifyPassword(string $plain, string $hash): bool {
        return password_verify($plain, $hash);
    }

    public static function dashboardStats(): array {
        return array_merge(BookingModel::stats(), [
            'users'    => UserModel::count(),
            'packages' => PackageModel::totalCount(),
            'reviews'  => ReviewModel::countPending(),
        ]);
    }
}
