<?php
/**
 * Author:  Kiran Khadka
 * Version: 1.0.0 (First edition)
 * Contact: +977-9869756622
 * Mail:    therealkiranda@gmail.com
 * © 2026 Kiran Khadka. All rights reserved.
 */
class SettingsModel {
    public static function all(): array {
        $rows = DB::fetchAll('SELECT setting_key, setting_value FROM settings');
        $out  = [];
        foreach ($rows as $r) $out[$r['setting_key']] = $r['setting_value'];
        return $out;
    }

    public static function set(string $key, string $value): void {
        DB::query(
            'INSERT INTO settings (setting_key, setting_value) VALUES (?,?) ON DUPLICATE KEY UPDATE setting_value = ?',
            [$key, $value, $value]
        );
    }

    public static function bulkSet(array $data): void {
        foreach ($data as $key => $value) self::set($key, $value);
    }
}
