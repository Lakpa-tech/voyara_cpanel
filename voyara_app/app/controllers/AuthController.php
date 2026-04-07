<?php
/**
 * Author:  Kiran Khadka
 * Version: 1.0.0 (First edition)
 * Contact: +977-9869756622
 * Mail:    therealkiranda@gmail.com
 * © 2026 Kiran Khadka. All rights reserved.
 */
require_once ROOT_PATH . '/config/bootstrap.php';

class AuthController {
    
    public static function loginForm(): void {
        if (Auth::check()) { redirect('/dashboard'); }
        view('user.login', ['error' => Session::getFlash('error'), 'success' => Session::getFlash('success')]);
    }

    public static function login(): void {
        CSRF::requireVerify();
        $v = new Validator($_POST);
        $v->required('email')->email('email')->required('password');

        if ($v->fails()) {
            Session::flash('error', implode(' ', $v->errors()));
            redirect('/login');
        }

        $user = UserModel::findByEmail($v->get('email'));
        if (!$user || !UserModel::verifyPassword($v->get('password'), $user['password'])) {
            Session::flash('error', 'Invalid email or password.');
            redirect('/login');
        }
        if (!$user['is_active']) {
            Session::flash('error', 'Your account has been disabled.');
            redirect('/login');
        }

        Auth::loginUser($user);
        redirect('/dashboard');
    }

    public static function registerForm(): void {
        if (Auth::check()) { redirect('/dashboard'); }
        view('user.register', ['error' => Session::getFlash('error')]);
    }

    public static function register(): void {
        CSRF::requireVerify();
        $v = new Validator($_POST);
        $v->required('name')->minLength('name', 2)->maxLength('name', 100)
          ->required('email')->email('email')
          ->required('password')->minLength('password', 8)
          ->required('password_confirm');

        if ($v->fails()) {
            Session::flash('error', implode(' ', $v->errors()));
            redirect('/register');
        }
        if ($v->get('password') !== $v->get('password_confirm')) {
            Session::flash('error', 'Passwords do not match.');
            redirect('/register');
        }
        if (UserModel::emailExists($v->get('email'))) {
            Session::flash('error', 'Email already registered.');
            redirect('/register');
        }

        $id   = UserModel::create($v->all());
        $user = UserModel::findById($id);
        Auth::loginUser($user);
        Session::flash('success', 'Welcome to Voyara! Your account is ready.');
        redirect('/dashboard');
    }

    public static function logout(): void {
        Auth::logout();
        redirect('/login');
    }
}
