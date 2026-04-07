<?php
/**
 * Author:  Kiran Khadka
 * Version: 1.0.0 (First edition)
 * Contact: +977-9869756622
 * Mail:    therealkiranda@gmail.com
 * © 2026 Kiran Khadka. All rights reserved.
 */
class CSRF {
    private const TOKEN_KEY = '_csrf_token';

    public static function token(): string {
        if (!Session::has(self::TOKEN_KEY)) {
            Session::set(self::TOKEN_KEY, bin2hex(random_bytes(32)));
        }
        return Session::get(self::TOKEN_KEY);
    }

    public static function field(): string {
        return '<input type="hidden" name="_csrf" value="' . e(self::token()) . '">';
    }

    public static function verify(): bool {
        $submitted = $_POST['_csrf'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
        $stored    = Session::get(self::TOKEN_KEY, '');
        if (!$stored || !hash_equals($stored, $submitted)) {
            return false;
        }
        
        Session::set(self::TOKEN_KEY, bin2hex(random_bytes(32)));
        return true;
    }

    public static function requireVerify(): void {
        if (!self::verify()) {
            http_response_code(403);
            die('CSRF token mismatch.');
        }
    }
}
