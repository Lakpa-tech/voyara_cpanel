<?php
/**
 * Author:  Kiran Khadka
 * Version: 1.0.0 (First edition)
 * Contact: +977-9869756622
 * Mail:    therealkiranda@gmail.com
 * © 2026 Kiran Khadka. All rights reserved.
 */
require_once ROOT_PATH . '/config/bootstrap.php';

class AgentController {
    public static function loginForm(): void {
        if (Auth::checkAgent()) redirect('/agent/dashboard');
        view('agent.login', ['error' => Session::getFlash('error')]);
    }

    public static function login(): void {
        CSRF::requireVerify();
        $v = new Validator($_POST);
        $v->required('email')->email('email')->required('password');
        if ($v->fails()) { Session::flash('error', 'All fields required.'); redirect('/agent/login'); }

        $agent = AgentModel::findByEmail($v->get('email'));
        if (!$agent || !AgentModel::verifyPassword($v->get('password'), $agent['password'])) {
            Session::flash('error', 'Invalid credentials.');
            redirect('/agent/login');
        }
        Auth::loginAgent($agent);
        redirect('/agent/dashboard');
    }

    public static function logout(): void {
        Auth::logout();
        redirect('/agent/login');
    }

    public static function dashboard(): void {
        Auth::requireAgent();
        $agent    = Auth::agent();
        $bookings = BookingModel::byAgent($agent['id'], 10);
        $stats    = [
            'total'     => AgentModel::bookingCount($agent['id']),
            'pending'   => (int) DB::fetchColumn("SELECT COUNT(*) FROM bookings WHERE agent_id = ? AND status='pending'",   [$agent['id']]),
            'confirmed' => (int) DB::fetchColumn("SELECT COUNT(*) FROM bookings WHERE agent_id = ? AND status='confirmed'", [$agent['id']]),
        ];
        view('agent.dashboard', compact('agent', 'bookings', 'stats'));
    }

    public static function bookings(): void {
        Auth::requireAgent();
        $agent    = Auth::agent();
        $page     = max(1, (int)($_GET['page'] ?? 1));
        $bookings = BookingModel::byAgent($agent['id'], 20, ($page - 1) * 20);
        $total    = AgentModel::bookingCount($agent['id']);
        view('agent.bookings', compact('agent', 'bookings', 'total', 'page'));
    }

    public static function bookingDetail(int $id): void {
        Auth::requireAgent();
        $agent   = Auth::agent();
        $booking = BookingModel::findById($id);
        if (!$booking || $booking['agent_id'] != $agent['id']) redirect('/agent/bookings');
        view('agent.booking_detail', [
            'booking' => $booking,
            'success' => Session::getFlash('success'),
            'error'   => Session::getFlash('error'),
        ]);
    }

    public static function updateStatus(int $id): void {
        Auth::requireAgent();
        CSRF::requireVerify();
        $agent   = Auth::agent();
        $booking = BookingModel::findById($id);
        if (!$booking || $booking['agent_id'] != $agent['id']) redirect('/agent/bookings');

        $allowed = ['confirmed','completed','cancelled'];
        $status  = $_POST['status'] ?? '';
        if (!in_array($status, $allowed, true)) redirect('/agent/bookings/' . $id);

        BookingModel::updateStatus($id, $status, $_POST['agent_notes'] ?? null);
        Session::flash('success', 'Booking updated to: ' . ucfirst($status));
        redirect('/agent/bookings/' . $id);
    }
}
