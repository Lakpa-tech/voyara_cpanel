<?php
/**
 * Author:  Kiran Khadka
 * Version: 1.0.0 (First edition)
 * Contact: +977-9869756622
 * Mail:    therealkiranda@gmail.com
 * © 2026 Kiran Khadka. All rights reserved.
 */
require_once ROOT_PATH . '/config/bootstrap.php';

class BookingController {
    
    public static function form(int $packageId): void {
        Auth::requireUser();
        $package = PackageModel::findById($packageId);
        if (!$package || !$package['is_active']) {
            redirect('/packages');
        }
        view('user.booking_form', [
            'package' => $package,
            'error'   => Session::getFlash('error'),
        ]);
    }

    public static function store(): void {
        Auth::requireUser();
        CSRF::requireVerify();
        $user = Auth::user();

        $v = new Validator($_POST);
        $v->required('package_id')->numeric('package_id')
          ->required('travel_date')->date('travel_date')->future('travel_date')
          ->required('persons')->numeric('persons')->min('persons', 1);

        if ($v->fails()) {
            Session::flash('error', implode(' ', $v->errors()));
            redirect('/book/' . (int)($_POST['package_id'] ?? 0));
        }

        $package = PackageModel::findById((int)$v->get('package_id'));
        if (!$package || !$package['is_active']) {
            redirect('/packages');
        }

        $persons    = (int)$v->get('persons');
        $totalPrice = $package['price'] * ($package['price_per'] === 'person' ? $persons : 1);

        try {
            $bookingId = BookingModel::create([
                'user_id'          => $user['id'],
                'package_id'       => $package['id'],
                'travel_date'      => $v->get('travel_date'),
                'persons'          => $persons,
                'total_price'      => $totalPrice,
                'special_requests' => $v->get('special_requests'),
            ]);
        } catch (Exception $e) {
            Session::flash('error', 'Booking failed. Please try again.');
            redirect('/book/' . $package['id']);
        }

        redirect('/bookings/' . $bookingId);
    }

    public static function show(int $id): void {
        Auth::requireUser();
        $user    = Auth::user();
        $booking = BookingModel::findById($id);

        if (!$booking || $booking['user_id'] != $user['id']) {
            redirect('/dashboard');
        }

        view('user.booking_detail', [
            'booking' => $booking,
            'success' => Session::getFlash('success'),
            'error'   => Session::getFlash('error'),
        ]);
    }

    public static function uploadPayment(int $id): void {
        Auth::requireUser();
        CSRF::requireVerify();
        $user    = Auth::user();
        $booking = BookingModel::findById($id);

        if (!$booking || $booking['user_id'] != $user['id'] || $booking['status'] !== 'pending') {
            redirect('/dashboard');
        }

        $v = new Validator($_POST);
        $v->required('transaction_ref')->minLength('transaction_ref', 3);
        if ($v->fails()) {
            Session::flash('error', implode(' ', $v->errors()));
            redirect('/bookings/' . $id);
        }

        $filename = null;
        if (!empty($_FILES['receipt']['name'])) {
            $uploader = new FileUploader();
            $filename = $uploader->upload(
                $_FILES['receipt'],
                UPLOAD_PATH . '/receipts',
                array_merge(ALLOWED_IMG_TYPES, ['application/pdf'])
            );
            if (!$filename) {
                Session::flash('error', $uploader->error());
                redirect('/bookings/' . $id);
            }
        }

        BookingModel::uploadReceipt($id, $filename ?? '', $v->get('transaction_ref'));
        Session::flash('success', 'Payment proof submitted. We\'ll verify and confirm your booking shortly.');
        redirect('/bookings/' . $id);
    }

    public static function cancel(int $id): void {
        Auth::requireUser();
        CSRF::requireVerify();
        $user    = Auth::user();
        $booking = BookingModel::findById($id);

        if (!$booking || $booking['user_id'] != $user['id']
            || !in_array($booking['status'], ['pending'], true)) {
            Session::flash('error', 'This booking cannot be cancelled.');
            redirect('/dashboard');
        }

        BookingModel::updateStatus($id, 'cancelled');
        Session::flash('success', 'Booking cancelled successfully.');
        redirect('/dashboard');
    }
}
