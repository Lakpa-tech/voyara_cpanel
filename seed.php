<?php
/**
 * Voyara Database Seeder
 * Populates the database with realistic dummy data for testing purposes.
 */

// Load configuration
require_once __DIR__ . '/voyara_app/config/database.php';

// Helper for strings
function slugify($text) {
    if (empty($text)) return 'n-a';
    return strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $text)));
}

try {
    $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
    $pdo = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);

    echo "Connection successful. Starting seeding...\n";

    // 1. CLEAR EXISTING DATA (optional but good for clean seed)
    // $pdo->exec("SET FOREIGN_KEY_CHECKS = 0;");
    // $pdo->exec("TRUNCATE TABLE reviews;");
    // $pdo->exec("TRUNCATE TABLE bookings;");
    // $pdo->exec("TRUNCATE TABLE itineraries;");
    // $pdo->exec("TRUNCATE TABLE package_images;");
    // $pdo->exec("TRUNCATE TABLE packages;");
    // $pdo->exec("SET FOREIGN_KEY_CHECKS = 1;");

    // 2. SEED USERS
    $users = [
        ['name' => 'Alice Johnson', 'email' => 'alice@example.com'],
        ['name' => 'Bob Smith', 'email' => 'bob@example.com'],
        ['name' => 'Charlie Brown', 'email' => 'charlie@example.com'],
        ['name' => 'Diana Prince', 'email' => 'diana@example.com'],
    ];

    $stmtUser = $pdo->prepare("INSERT IGNORE INTO users (name, email, password) VALUES (?, ?, ?)");
    $password = password_hash('User@123', PASSWORD_DEFAULT);
    foreach ($users as $u) {
        $stmtUser->execute([$u['name'], $u['email'], $password]);
    }
    echo "Users seeded.\n";

    // 3. GET CATEGORIES & LOCATIONS (assuming they exist from database.sql)
    $categories = $pdo->query("SELECT id, name FROM categories")->fetchAll();
    $locations = $pdo->query("SELECT id, name, country FROM locations")->fetchAll();

    if (!$categories || !$locations) {
        die("Error: Categories or Locations missing. Please run database.sql first.\n");
    }

    // 4. SEED PACKAGES
    $packageData = [
        [
            'title' => 'Bali Zen Retreat',
            'short_desc' => 'Reconnect with your inner self amidst the lush rice terraces and spiritual energy of Ubud.',
            'price' => 1250,
            'duration' => 7,
            'cat' => 'Honeymoon',
            'loc' => 'Bali',
            'img' => 'https://images.unsplash.com/photo-1537996194471-e657df975ab4?auto=format&fit=crop&w=800&q=80'
        ],
        [
            'title' => 'Santorini Sunset Odyssey',
            'short_desc' => 'Sail the caldera and witness the world\'s most famous sunset from the comfort of a private yacht.',
            'price' => 2100,
            'duration' => 5,
            'cat' => 'International',
            'loc' => 'Santorini',
            'img' => 'https://images.unsplash.com/photo-1570077188670-e3a8d69ac5ff?auto=format&fit=crop&w=800&q=80'
        ],
        [
            'title' => 'Kyoto Golden Autumn',
            'short_desc' => 'Experience the ethereal beauty of Kyoto\'s ancient temples draped in vibrant autumn leaves.',
            'price' => 1850,
            'duration' => 6,
            'cat' => 'Cultural',
            'loc' => 'Kyoto',
            'img' => 'https://images.unsplash.com/photo-1493976040374-85c8e12f0c0e?auto=format&fit=crop&w=800&q=80'
        ],
        [
            'title' => 'Maldives Overwater Sanctuary',
            'short_desc' => 'Wake up to the sound of waves in your private villa perched over crystal-clear lagoon waters.',
            'price' => 3500,
            'duration' => 10,
            'cat' => 'Beach',
            'loc' => 'Maldives',
            'img' => 'https://images.unsplash.com/photo-1514282401047-d79a71a590e8?auto=format&fit=crop&w=800&q=80'
        ],
        [
            'title' => 'Amalfi Coast Vintage Drive',
            'short_desc' => 'Drive down the iconic coastline in a classic Alfa Romeo, stopping at hidden pebbled coves.',
            'price' => 2800,
            'duration' => 8,
            'cat' => 'International',
            'loc' => 'Amalfi Coast',
            'img' => 'https://images.unsplash.com/photo-1533105079780-92b9be482077?auto=format&fit=crop&w=800&q=80'
        ],
        [
            'title' => 'Patagonia Glacial Expedition',
            'short_desc' => 'Trek across the mighty Perito Moreno Glacier and witness the raw power of the Southern Andes.',
            'price' => 4200,
            'duration' => 12,
            'cat' => 'Adventure',
            'loc' => 'Patagonia',
            'img' => 'https://images.unsplash.com/photo-1517059224940-d4af9eec41b7?auto=format&fit=crop&w=800&q=80'
        ],
    ];

    $stmtPkg = $pdo->prepare("INSERT IGNORE INTO packages (category_id, location_id, title, slug, short_desc, description, price, duration_days, is_featured, is_active) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 1, 1)");

    foreach ($packageData as $p) {
        $catId = 1;
        foreach ($categories as $cat) if ($cat['name'] == $p['cat']) $catId = $cat['id'];
        $locId = 1;
        foreach ($locations as $loc) if ($loc['name'] == $p['loc']) $locId = $loc['id'];

        $pkgSlug = slugify($p['title']);
        $stmtPkg->execute([
            $catId, $locId, $p['title'], $pkgSlug, $p['short_desc'],
            "A comprehensive description of " . $p['title'] . " covering all the luxurious details and exclusive experiences included in this bespoke travel package.",
            $p['price'], $p['duration']
        ]);

        $pkgId = $pdo->lastInsertId();
        if (!$pkgId) {
            $pkgId = $pdo->query("SELECT id FROM packages WHERE slug = '$pkgSlug'")->fetchColumn();
        }

        // 5. ITINERARIES
        $stmtItin = $pdo->prepare("INSERT IGNORE INTO itineraries (package_id, day_number, title, description) VALUES (?, ?, ?, ?)");
        for ($d = 1; $d <= 3; $d++) {
            $stmtItin->execute([$pkgId, $d, "Day $d: Exploration", "A detailed plan for day $d, including morning activities, lunch stops, and evening relaxation."]);
        }

        // 6. REVIEWS
        $stmtRev = $pdo->prepare("INSERT IGNORE INTO reviews (user_id, package_id, booking_id, rating, title, body, status) VALUES (?, ?, ?, ?, ?, ?, ?)");
        // We need a booking first for the review table constraint (uq_booking_review)
        // But for dummy data, we might need to be careful with IDs.
        // Let's create a dummy booking first.
        $stmtBook = $pdo->prepare("INSERT IGNORE INTO bookings (booking_ref, user_id, package_id, travel_date, total_price, status) VALUES (?, ?, ?, ?, ?, ?)");
        $ref = strtoupper(substr(md5($pkgId . time()), 0, 8));
        $userId = $pdo->query("SELECT id FROM users LIMIT 1")->fetchColumn();
        $stmtBook->execute([$ref, $userId, $pkgId, date('Y-m-d', strtotime('+1 month')), $p['price'], 'confirmed']);
        $bookId = $pdo->lastInsertId();

        if ($bookId) {
            $stmtRev->execute([$userId, $pkgId, $bookId, 5, "Perfect Trip!", "Everything was organized to perfection. Truly a life-changing experience.", 'approved']);
        }
    }

    echo "Packages, Itineraries, and Reviews seeded.\n";
    echo "Seeding completed successfully!\n";

} catch (PDOException $e) {
    die("Database Error: " . $e->getMessage() . "\n");
}
