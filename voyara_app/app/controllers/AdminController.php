<?php
/**
 * Author:  Kiran Khadka
 * Version: 1.0.0 (First edition)
 * Contact: +977-9869756622
 * Mail:    therealkiranda@gmail.com
 * © 2026 Kiran Khadka. All rights reserved.
 */
require_once ROOT_PATH . '/config/bootstrap.php';

class AdminController {

    public static function loginForm(): void {
        if (Auth::checkAdmin()) redirect('/admin/dashboard');
        view('admin.login', ['error' => Session::getFlash('error')]);
    }

    public static function login(): void {
        CSRF::requireVerify();
        $v = new Validator($_POST);
        $v->required('email')->email('email')->required('password');
        if ($v->fails()) { Session::flash('error', 'All fields required.'); redirect('/admin/login'); }

        $admin = AdminModel::findByEmail($v->get('email'));
        if (!$admin || !AdminModel::verifyPassword($v->get('password'), $admin['password'])) {
            Session::flash('error', 'Invalid credentials.');
            redirect('/admin/login');
        }
        Auth::loginAdmin($admin);
        redirect('/admin/dashboard');
    }

    public static function logout(): void {
        Auth::logout();
        redirect('/admin/login');
    }

    public static function dashboard(): void {
        Auth::requireAdmin();
        $stats           = AdminModel::dashboardStats();
        $recentBookings  = BookingModel::all([], 8);
        $pendingPayments = BookingModel::all(['status' => 'pending'], 5);
        view('admin.dashboard', compact('stats', 'recentBookings', 'pendingPayments'));
    }

    public static function packages(): void {
        Auth::requireAdmin();
        $page     = max(1, (int)($_GET['page'] ?? 1));
        $packages = PackageModel::allForAdmin(20, ($page - 1) * 20);
        $total    = PackageModel::totalCount();
        view('admin.packages.index', compact('packages', 'total', 'page'));
    }

    public static function createPackageForm(): void {
        Auth::requireAdmin();
        $categories = PackageModel::categories();
        $locations  = PackageModel::locations();
        view('admin.packages.form', [
            'package'    => null,
            'categories' => $categories,
            'locations'  => $locations,
            'error'      => Session::getFlash('error'),
        ]);
    }

    public static function storePackage(): void {
        Auth::requireAdmin();
        CSRF::requireVerify();

        $v = new Validator($_POST);
        $v->required('title')->required('category_id')->required('location_id')
          ->required('price')->numeric('price')->min('price', 0)
          ->required('duration_days')->numeric('duration_days')->min('duration_days', 1)
          ->required('description');

        if ($v->fails()) {
            Session::flash('error', implode(' ', $v->errors()));
            redirect('/admin/packages/create');
        }

        $slug = PackageModel::makeSlug($v->get('title'));

        $coverImage = null;
        if (!empty($_FILES['cover_image']['name'])) {
            $up = new FileUploader();
            $coverImage = $up->upload($_FILES['cover_image'], UPLOAD_PATH . '/packages');
            if (!$coverImage) { Session::flash('error', $up->error()); redirect('/admin/packages/create'); }
        }

        $pkgId = PackageModel::create([
            'category_id'   => (int)$v->get('category_id'),
            'location_id'   => (int)$v->get('location_id'),
            'title'         => $v->get('title'),
            'slug'          => $slug,
            'short_desc'    => $v->get('short_desc'),
            'description'   => $v->get('description'),
            'price'         => (float)$v->get('price'),
            'price_per'     => in_array($v->get('price_per'), ['person','group'], true) ? $v->get('price_per') : 'person',
            'duration_days' => (int)$v->get('duration_days'),
            'max_persons'   => $v->get('max_persons') ? (int)$v->get('max_persons') : null,
            'cover_image'   => $coverImage,
            'is_featured'   => isset($_POST['is_featured']) ? 1 : 0,
            'is_active'     => isset($_POST['is_active'])   ? 1 : 0,
        ]);

        if (!empty($_POST['itinerary_title'])) {
            $days = [];
            foreach ($_POST['itinerary_title'] as $i => $t) {
                if (trim($t)) $days[] = [
                    'day_number'    => $i + 1,
                    'title'         => $t,
                    'description'   => $_POST['itinerary_desc'][$i]  ?? '',
                    'meals'         => $_POST['itinerary_meals'][$i]  ?? null,
                    'accommodation' => $_POST['itinerary_hotel'][$i]  ?? null,
                ];
            }
            PackageModel::saveItineraries($pkgId, $days);
        }

        PackageModel::saveInclusions(
            $pkgId,
            array_filter(explode("\n", $_POST['inclusions'] ?? '')),
            array_filter(explode("\n", $_POST['exclusions'] ?? ''))
        );

        if (!empty($_FILES['gallery']['name'][0])) {
            $up = new FileUploader();
            foreach ($_FILES['gallery']['name'] as $i => $name) {
                if (!$name) continue;
                $file = ['name' => $name, 'type' => $_FILES['gallery']['type'][$i],
                         'tmp_name' => $_FILES['gallery']['tmp_name'][$i],
                         'error' => $_FILES['gallery']['error'][$i],
                         'size'  => $_FILES['gallery']['size'][$i]];
                $fn = $up->upload($file, UPLOAD_PATH . '/packages');
                if ($fn) PackageModel::addImage($pkgId, $fn, $i);
            }
        }

        Session::flash('success', 'Package created successfully.');
        redirect('/admin/packages');
    }

    public static function editPackageForm(int $id): void {
        Auth::requireAdmin();
        $package    = PackageModel::findBySlug(
            DB::fetchColumn('SELECT slug FROM packages WHERE id = ?', [$id]) ?: ''
        );
        if (!$package) redirect('/admin/packages');
        $categories = PackageModel::categories();
        $locations  = PackageModel::locations();
        view('admin.packages.form', [
            'package'    => $package,
            'categories' => $categories,
            'locations'  => $locations,
            'error'      => Session::getFlash('error'),
        ]);
    }

    public static function updatePackage(int $id): void {
        Auth::requireAdmin();
        CSRF::requireVerify();

        $v = new Validator($_POST);
        $v->required('title')->required('category_id')->required('location_id')
          ->required('price')->numeric('price')
          ->required('duration_days')->numeric('duration_days');

        if ($v->fails()) {
            Session::flash('error', implode(' ', $v->errors()));
            redirect('/admin/packages/' . $id . '/edit');
        }

        $data = [
            'category_id'   => (int)$v->get('category_id'),
            'location_id'   => (int)$v->get('location_id'),
            'title'         => $v->get('title'),
            'short_desc'    => $v->get('short_desc'),
            'description'   => $v->get('description'),
            'price'         => (float)$v->get('price'),
            'price_per'     => in_array($v->get('price_per'), ['person','group'], true) ? $v->get('price_per') : 'person',
            'duration_days' => (int)$v->get('duration_days'),
            'max_persons'   => $v->get('max_persons') ? (int)$v->get('max_persons') : null,
            'is_featured'   => isset($_POST['is_featured']) ? 1 : 0,
            'is_active'     => isset($_POST['is_active'])   ? 1 : 0,
        ];

        if (!empty($_FILES['cover_image']['name'])) {
            $up = new FileUploader();
            $fn = $up->upload($_FILES['cover_image'], UPLOAD_PATH . '/packages');
            if ($fn) {
                $old = DB::fetchColumn('SELECT cover_image FROM packages WHERE id = ?', [$id]);
                if ($old) FileUploader::delete(UPLOAD_PATH . '/packages/' . $old);
                $data['cover_image'] = $fn;
            }
        }

        PackageModel::update($id, $data);

        if (!empty($_POST['itinerary_title'])) {
            $days = [];
            foreach ($_POST['itinerary_title'] as $i => $t) {
                if (trim($t)) $days[] = [
                    'day_number'    => $i + 1,
                    'title'         => $t,
                    'description'   => $_POST['itinerary_desc'][$i]  ?? '',
                    'meals'         => $_POST['itinerary_meals'][$i]  ?? null,
                    'accommodation' => $_POST['itinerary_hotel'][$i]  ?? null,
                ];
            }
            PackageModel::saveItineraries($id, $days);
        }

        PackageModel::saveInclusions(
            $id,
            array_filter(explode("\n", $_POST['inclusions'] ?? '')),
            array_filter(explode("\n", $_POST['exclusions'] ?? ''))
        );

        Session::flash('success', 'Package updated.');
        redirect('/admin/packages');
    }

    public static function deletePackage(int $id): void {
        Auth::requireAdmin();
        CSRF::requireVerify();
        $pkg = PackageModel::findById($id);
        if ($pkg && $pkg['cover_image']) {
            FileUploader::delete(UPLOAD_PATH . '/packages/' . $pkg['cover_image']);
        }
        PackageModel::delete($id);
        Session::flash('success', 'Package deleted.');
        redirect('/admin/packages');
    }

    public static function bookings(): void {
        Auth::requireAdmin();
        $page     = max(1, (int)($_GET['page'] ?? 1));
        $filters  = ['status' => $_GET['status'] ?? '', 'keyword' => $_GET['keyword'] ?? ''];
        $bookings = BookingModel::all($filters, 20, ($page - 1) * 20);
        $total    = BookingModel::countAll($filters);
        $agents   = AgentModel::all();
        view('admin.bookings.index', compact('bookings', 'total', 'page', 'filters', 'agents'));
    }

    public static function bookingDetail(int $id): void {
        Auth::requireAdmin();
        $booking = BookingModel::findById($id);
        if (!$booking) redirect('/admin/bookings');
        $agents = AgentModel::all();
        view('admin.bookings.detail', [
            'booking' => $booking,
            'agents'  => $agents,
            'success' => Session::getFlash('success'),
            'error'   => Session::getFlash('error'),
        ]);
    }

    public static function updateBookingStatus(int $id): void {
        Auth::requireAdmin();
        CSRF::requireVerify();
        $allowed = ['pending','confirmed','completed','cancelled'];
        $status  = $_POST['status'] ?? '';
        if (!in_array($status, $allowed, true)) redirect('/admin/bookings/' . $id);

        BookingModel::updateStatus($id, $status, $_POST['admin_notes'] ?? null);
        Session::flash('success', 'Booking status updated.');
        redirect('/admin/bookings/' . $id);
    }

    public static function assignAgent(int $id): void {
        Auth::requireAdmin();
        CSRF::requireVerify();
        $agentId = (int)($_POST['agent_id'] ?? 0);
        if ($agentId) BookingModel::assignAgent($id, $agentId);
        Session::flash('success', 'Agent assigned.');
        redirect('/admin/bookings/' . $id);
    }

    public static function verifyPayment(int $bookingId): void {
        Auth::requireAdmin();
        CSRF::requireVerify();
        $admin  = Auth::admin();
        $status = $_POST['payment_status'] ?? 'verified';
        if (!in_array($status, ['verified', 'rejected'], true)) redirect('/admin/bookings/' . $bookingId);

        BookingModel::verifyPayment($bookingId, $admin['id'], $status);
        Session::flash('success', 'Payment ' . $status . '.');
        redirect('/admin/bookings/' . $bookingId);
    }

    public static function users(): void {
        Auth::requireAdmin();
        $page  = max(1, (int)($_GET['page'] ?? 1));
        $users = UserModel::all(20, ($page - 1) * 20);
        $total = UserModel::count();
        view('admin.users.index', compact('users', 'total', 'page'));
    }

    public static function toggleUser(int $id): void {
        Auth::requireAdmin();
        CSRF::requireVerify();
        UserModel::toggleActive($id);
        Session::flash('success', 'User status updated.');
        redirect('/admin/users');
    }

    public static function reviews(): void {
        Auth::requireAdmin();
        $status  = $_GET['status'] ?? 'pending';
        $reviews = ReviewModel::all($status, 30);
        view('admin.reviews.index', compact('reviews', 'status'));
    }

    public static function moderateReview(int $id): void {
        Auth::requireAdmin();
        CSRF::requireVerify();
        $status = $_POST['status'] ?? '';
        if (!in_array($status, ['approved', 'rejected'], true)) redirect('/admin/reviews');
        ReviewModel::updateStatus($id, $status);
        Session::flash('success', 'Review ' . $status . '.');
        redirect('/admin/reviews');
    }

    public static function agents(): void {
        Auth::requireAdmin();
        $agents = AgentModel::all();
        view('admin.agents.index', ['agents' => $agents, 'success' => Session::getFlash('success'), 'error' => Session::getFlash('error')]);
    }

    public static function createAgentForm(): void {
        Auth::requireAdmin();
        view('admin.agents.form', ['error' => Session::getFlash('error')]);
    }

    public static function storeAgent(): void {
        Auth::requireAdmin();
        CSRF::requireVerify();
        $v = new Validator($_POST);
        $v->required('name')->required('email')->email('email')->required('password')->minLength('password', 8);
        if ($v->fails()) { Session::flash('error', implode(' ', $v->errors())); redirect('/admin/agents/create'); }

        AgentModel::create($v->all());
        Session::flash('success', 'Agent created.');
        redirect('/admin/agents');
    }

    public static function settings(): void {
        Auth::requireAdmin();
        $settings = SettingsModel::all();
        view('admin.settings', ['settings' => $settings, 'success' => Session::getFlash('success')]);
    }

    public static function saveSettings(): void {
        Auth::requireAdmin();
        CSRF::requireVerify();
        $allowed = ['site_name','site_email','site_phone','bank_name','bank_account',
                    'bank_routing','bank_account_name','bank_swift','currency_symbol','currency_code'];
        $data = [];
        foreach ($allowed as $k) {
            $data[$k] = strip_tags($_POST[$k] ?? '');
        }
        SettingsModel::bulkSet($data);
        Session::flash('success', 'Settings saved.');
        redirect('/admin/settings');
    }
}
