<?php
/**
 * Author:  Kiran Khadka
 * Version: 1.0.0 (First edition)
 * Contact: +977-9869756622
 * Mail:    therealkiranda@gmail.com
 * © 2026 Kiran Khadka. All rights reserved.
 */
class Auth {
    
    public static function loginUser(array $user): void {
        Session::set('user_id',   $user['id']);
        Session::set('user_name', $user['name']);
        Session::set('user_role', 'user');
    }

    public static function user(): ?array {
        if (Session::get('user_role') !== 'user') return null;
        $id = Session::get('user_id');
        if (!$id) return null;
        return DB::fetchOne('SELECT * FROM users WHERE id = ? AND is_active = 1', [$id]);
    }

    public static function check(): bool {
        return Session::get('user_role') === 'user' && Session::has('user_id');
    }

    public static function requireUser(): void {
        if (!self::check()) {
            Session::flash('error', 'Please login to continue.');
            redirect('/login');
        }
    }

    public static function loginAdmin(array $admin): void {
        Session::set('admin_id',   $admin['id']);
        Session::set('admin_name', $admin['name']);
        Session::set('user_role',  'admin');
        DB::query('UPDATE admins SET last_login = NOW() WHERE id = ?', [$admin['id']]);
    }

    public static function admin(): ?array {
        if (Session::get('user_role') !== 'admin') return null;
        $id = Session::get('admin_id');
        if (!$id) return null;
        return DB::fetchOne('SELECT * FROM admins WHERE id = ? AND is_active = 1', [$id]);
    }

    public static function checkAdmin(): bool {
        return Session::get('user_role') === 'admin' && Session::has('admin_id');
    }

    public static function requireAdmin(): void {
        if (!self::checkAdmin()) {
            Session::flash('error', 'Admin access required.');
            redirect('/admin/login');
        }
    }

    public static function loginAgent(array $agent): void {
        Session::set('agent_id',   $agent['id']);
        Session::set('agent_name', $agent['name']);
        Session::set('user_role',  'agent');
        DB::query('UPDATE agents SET last_login = NOW() WHERE id = ?', [$agent['id']]);
    }

    public static function agent(): ?array {
        if (Session::get('user_role') !== 'agent') return null;
        $id = Session::get('agent_id');
        if (!$id) return null;
        return DB::fetchOne('SELECT * FROM agents WHERE id = ? AND is_active = 1', [$id]);
    }

    public static function checkAgent(): bool {
        return Session::get('user_role') === 'agent' && Session::has('agent_id');
    }

    public static function requireAgent(): void {
        if (!self::checkAgent()) {
            Session::flash('error', 'Agent access required.');
            redirect('/agent/login');
        }
    }

    public static function logout(): void {
        Session::destroy();
    }
}
