<?php
/**
 * Author:  Kiran Khadka
 * Version: 1.0.0 (First edition)
 * Contact: +977-9869756622
 * Mail:    therealkiranda@gmail.com
 * © 2026 Kiran Khadka. All rights reserved.
 */
require_once ROOT_PATH . '/config/bootstrap.php';

class PackageController {
    private const PER_PAGE = 9;

    public static function index(): void {
        $page    = max(1, (int)($_GET['page'] ?? 1));
        $offset  = ($page - 1) * self::PER_PAGE;
        $filters = self::extractFilters();

        $packages   = PackageModel::all($filters, self::PER_PAGE, $offset);
        $total      = PackageModel::count($filters);
        $totalPages = (int) ceil($total / self::PER_PAGE);
        $categories = PackageModel::categories();
        $locations  = PackageModel::locations();

        view('user.packages', compact(
            'packages', 'filters', 'page', 'total', 'totalPages', 'categories', 'locations'
        ));
    }

    public static function show(string $slug): void {
        $package = PackageModel::findBySlug($slug);
        if (!$package) {
            http_response_code(404);
            view('partials.404');
            return;
        }
        PackageModel::incrementViews($package['id']);
        $reviews  = ReviewModel::byPackage($package['id'], 5);
        $related  = PackageModel::all(['category' => $package['category_slug']], 3);
        $canReview = false;

        if (Auth::check()) {
            $user     = Auth::user();
            $bookings = BookingModel::byUser($user['id']);
            foreach ($bookings as $b) {
                if ($b['package_id'] == $package['id'] && $b['status'] === 'completed'
                    && !ReviewModel::existsForBooking($b['id'])) {
                    $canReview = $b['id'];
                    break;
                }
            }
        }

        view('user.package_detail', compact('package', 'reviews', 'related', 'canReview'));
    }

    private static function extractFilters(): array {
        $allowed = ['keyword', 'category', 'location', 'min_price', 'max_price', 'min_days', 'max_days'];
        $filters = [];
        foreach ($allowed as $k) {
            if (!empty($_GET[$k])) $filters[$k] = htmlspecialchars(strip_tags($_GET[$k]));
        }
        return $filters;
    }
}
