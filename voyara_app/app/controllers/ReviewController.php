<?php
/**
 * Author:  Kiran Khadka
 * Version: 1.0.0 (First edition)
 * Contact: +977-9869756622
 * Mail:    therealkiranda@gmail.com
 * © 2026 Kiran Khadka. All rights reserved.
 */
require_once ROOT_PATH . '/config/bootstrap.php';

class ReviewController {
    
    public static function store(): void {
        Auth::requireUser();
        CSRF::requireVerify();
        $user = Auth::user();

        $v = new Validator($_POST);
        $v->required('booking_id')->numeric('booking_id')
          ->required('rating')->numeric('rating')->min('rating', 1)
          ->required('body')->minLength('body', 10)->maxLength('body', 2000);

        if ($v->fails()) {
            Session::flash('error', implode(' ', $v->errors()));
            redirect('/dashboard');
        }

        $bookingId = (int)$v->get('booking_id');
        $booking   = BookingModel::findById($bookingId);

        if (!$booking || $booking['user_id'] != $user['id']
            || $booking['status'] !== 'completed'
            || ReviewModel::existsForBooking($bookingId)) {
            Session::flash('error', 'You are not eligible to review this booking.');
            redirect('/dashboard');
        }

        ReviewModel::create([
            'user_id'    => $user['id'],
            'package_id' => $booking['package_id'],
            'booking_id' => $bookingId,
            'rating'     => min(5, max(1, (int)$v->get('rating'))),
            'title'      => $v->get('title'),
            'body'       => $v->get('body'),
        ]);

        Session::flash('success', 'Review submitted! It will appear after moderation.');
        redirect('/dashboard');
    }
}
