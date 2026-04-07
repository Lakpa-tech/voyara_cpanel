<?php
/**
 * Author:  Kiran Khadka
 * Version: 1.0.0 (First edition)
 * Contact: +977-9869756622
 * Mail:    therealkiranda@gmail.com
 * © 2026 Kiran Khadka. All rights reserved.
 */
require_once ROOT_PATH . '/config/bootstrap.php';

class UserController {
    
    public static function dashboard(): void {
        Auth::requireUser();
        $user     = Auth::user();
        $bookings = BookingModel::byUser($user['id']);
        view('user.dashboard', compact('user', 'bookings'));
    }

    public static function profileForm(): void {
        Auth::requireUser();
        $user = Auth::user();
        view('user.profile', [
            'user'    => $user,
            'error'   => Session::getFlash('error'),
            'success' => Session::getFlash('success'),
        ]);
    }

    public static function updateProfile(): void {
        Auth::requireUser();
        CSRF::requireVerify();
        $user = Auth::user();

        $v = new Validator($_POST);
        $v->required('name')->minLength('name', 2)->maxLength('name', 150);

        if ($v->fails()) {
            Session::flash('error', implode(' ', $v->errors()));
            redirect('/profile');
        }

        $data = ['name' => $v->get('name'), 'phone' => $v->get('phone')];

        if (!empty($_FILES['avatar']['name'])) {
            $uploader = new FileUploader();
            $filename = $uploader->upload(
                $_FILES['avatar'],
                UPLOAD_PATH . '/avatars',
                ALLOWED_IMG_TYPES,
                2 * 1024 * 1024
            );
            if (!$filename) {
                Session::flash('error', $uploader->error());
                redirect('/profile');
            }
            
            if ($user['avatar']) {
                FileUploader::delete(UPLOAD_PATH . '/avatars/' . $user['avatar']);
            }
            $data['avatar'] = $filename;
        }

        UserModel::update($user['id'], $data);
        Session::set('user_name', $data['name']);
        Session::flash('success', 'Profile updated successfully.');
        redirect('/profile');
    }

    public static function changePassword(): void {
        Auth::requireUser();
        CSRF::requireVerify();
        $user = Auth::user();

        $v = new Validator($_POST);
        $v->required('current_password')
          ->required('new_password')->minLength('new_password', 8)
          ->required('confirm_password');

        if ($v->fails()) {
            Session::flash('error', implode(' ', $v->errors()));
            redirect('/profile');
        }
        if (!UserModel::verifyPassword($v->get('current_password'), $user['password'])) {
            Session::flash('error', 'Current password is incorrect.');
            redirect('/profile');
        }
        if ($v->get('new_password') !== $v->get('confirm_password')) {
            Session::flash('error', 'New passwords do not match.');
            redirect('/profile');
        }

        UserModel::updatePassword($user['id'], $v->get('new_password'));
        Session::flash('success', 'Password changed successfully.');
        redirect('/profile');
    }
}
