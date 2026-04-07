<?php
/**
 * Voyara Database Seeder - Robust Edition
 * Populates the database with realistic dummy data for testing purposes.
 * Author: Antigravity AI
 * Version: 2.0.0
 */

// Load configuration
require_once __DIR__ . '/voyara_app/config/database.php';

// Helper for strings
function slugify($text) {
    if (empty($text)) return 'n-a';
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
    $text = preg_replace('~[^-\w]+~', '', $text);
    $text = trim($text, '-');
    $text = preg_replace('~-+~', '-', $text);
    $text = strtolower($text);
    return empty($text) ? 'n-a' : $text;
}

// Simple color output helper
function logMsg($msg, $type = 'info') {
    $colors = [
        'info'    => "\033[0;32m", // Green
        'warn'    => "\033[1;33m", // Yellow
        'error'   => "\033[0;31m", // Red
        'reset'   => "\033[0m"
    ];
    echo $colors[$type] . $msg . $colors['reset'] . PHP_EOL;
}

try {
    $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
    $pdo = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);

    logMsg("Connected to " . DB_NAME . ". Starting robust seeding...");

    // --- 1. SEED ADMINS ---
    logMsg("Seeding Admins...");
    $admins = [
        ['name' => 'Super Admin', 'email' => 'admin@voyara.com', 'password' => 'Admin@123'],
        ['name' => 'Kiran Khadka', 'email' => 'kiran@voyara.com', 'password' => 'Kiran@123'],
        ['name' => 'Staff Manager', 'email' => 'staff@voyara.com', 'password' => 'Staff@123'],
    ];

    $stmtAdmin = $pdo->prepare("INSERT IGNORE INTO admins (name, email, password, is_active) VALUES (?, ?, ?, 1)");
    foreach ($admins as $a) {
        $hash = password_hash($a['password'], PASSWORD_BCRYPT, ['cost' => 12]);
        $stmtAdmin->execute([$a['name'], $a['email'], $hash]);
    }
    logMsg("  - Admins seeded.");

    // --- 2. SEED AGENTS ---
    logMsg("Seeding Agents...");
    $agents = [
        ['name' => 'John Travel', 'email' => 'john@voyara.com', 'phone' => '9800000001', 'bio' => 'Expert in European tours.'],
        ['name' => 'Sarah Compass', 'email' => 'sarah@voyara.com', 'phone' => '9800000002', 'bio' => 'Adventurer and mountain guide.'],
        ['name' => 'Mike Navigator', 'email' => 'mike@voyara.com', 'phone' => '9800000003', 'bio' => 'Specializes in Japanese culture.'],
    ];

    $stmtAgent = $pdo->prepare("INSERT IGNORE INTO agents (name, email, phone, password, bio, is_active) VALUES (?, ?, ?, ?, ?, 1)");
    foreach ($agents as $ag) {
        $hash = password_hash('Agent@123', PASSWORD_BCRYPT, ['cost' => 12]);
        $stmtAgent->execute([$ag['name'], $ag['email'], $ag['phone'], $hash, $ag['bio']]);
    }
    logMsg("  - Agents seeded.");

    // --- 3. SEED USERS ---
    logMsg("Seeding Users...");
    $users = [
        ['name' => 'Alice Johnson', 'email' => 'alice@example.com', 'phone' => '9841000001'],
        ['name' => 'Bob Smith', 'email' => 'bob@example.com', 'phone' => '9841000002'],
        ['name' => 'Charlie Brown', 'email' => 'charlie@example.com', 'phone' => '9841000003'],
        ['name' => 'Diana Prince', 'email' => 'diana@example.com', 'phone' => '9841000004'],
        ['name' => 'Ethan Hunt', 'email' => 'ethan@example.com', 'phone' => '9841000005'],
        ['name' => 'Fiona Gallagher', 'email' => 'fiona@example.com', 'phone' => '9841000006'],
    ];

    $stmtUser = $pdo->prepare("INSERT IGNORE INTO users (name, email, phone, password, is_active) VALUES (?, ?, ?, ?, 1)");
    $userPassword = password_hash('User@123', PASSWORD_BCRYPT, ['cost' => 12]);
    foreach ($users as $u) {
        $stmtUser->execute([$u['name'], $u['email'], $u['phone'], $userPassword]);
    }
    logMsg("  - Users seeded.");

    // --- 4. CHECK CATEGORIES & LOCATIONS ---
    $categories = $pdo->query("SELECT id, name FROM categories")->fetchAll();
    $locations = $pdo->query("SELECT id, name, country FROM locations")->fetchAll();

    if (empty($categories) || empty($locations)) {
        logMsg("Categories or Locations missing. Seeding basic ones...", 'warn');
        // Basic Categories
        $pdo->exec("INSERT IGNORE INTO categories (name, slug, icon) VALUES 
            ('Domestic', 'domestic', 'bi-house-door'),
            ('International', 'international', 'bi-globe'),
            ('Adventure', 'adventure', 'bi-mountain'),
            ('Honeymoon', 'honeymoon', 'bi-heart')");
        // Basic Locations
        $pdo->exec("INSERT IGNORE INTO locations (name, country, slug) VALUES 
            ('Bali', 'Indonesia', 'bali-indonesia'),
            ('Santorini', 'Greece', 'santorini-greece'),
            ('Pokhara', 'Nepal', 'pokhara-nepal')");
        
        $categories = $pdo->query("SELECT id, name FROM categories")->fetchAll();
        $locations = $pdo->query("SELECT id, name, country FROM locations")->fetchAll();
    }

    // --- 5. SEED PACKAGES ---
    logMsg("Seeding Packages...");
    $packageData = [
        [
            'title' => 'Bali Zen Retreat',
            'short_desc' => 'Reconnect with your inner self amidst the lush rice terraces and spiritual energy of Ubud.',
            'price' => 1250,
            'duration' => 7,
            'cat' => 'Honeymoon',
            'loc' => 'Bali',
            'featured' => 1
        ],
        [
            'title' => 'Santorini Sunset Odyssey',
            'short_desc' => 'Sail the caldera and witness the world\'s most famous sunset from the comfort of a private yacht.',
            'price' => 2100,
            'duration' => 5,
            'cat' => 'International',
            'loc' => 'Santorini',
            'featured' => 1
        ],
        [
            'title' => 'Pokhara Adventure Trek',
            'short_desc' => 'Explore the majestic Annapurna range and relax by the serene Phewa Lake.',
            'price' => 450,
            'duration' => 4,
            'cat' => 'Adventure',
            'loc' => 'Pokhara',
            'featured' => 1
        ],
        [
            'title' => 'Mount Everest Base Camp',
            'short_desc' => 'The ultimate trek to the roof of the world. A challenge for the brave.',
            'price' => 1800,
            'duration' => 14,
            'cat' => 'Adventure',
            'loc' => 'Pokhara',
            'featured' => 0
        ],
    ];

    $stmtPkg = $pdo->prepare("INSERT IGNORE INTO packages (category_id, location_id, title, slug, short_desc, description, price, duration_days, is_featured, is_active) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 1)");
    
    foreach ($packageData as $p) {
        $catId = $categories[0]['id'];
        foreach ($categories as $cat) {
            if (stripos($cat['name'], $p['cat']) !== false) {
                $catId = $cat['id'];
                break;
            }
        }
        
        $locId = $locations[0]['id'];
        foreach ($locations as $loc) {
            if (stripos($loc['name'], $p['loc']) !== false) {
                $locId = $loc['id'];
                break;
            }
        }

        $pkgSlug = slugify($p['title']);
        $description = "Discover the magic of " . $p['title'] . ". This package offers a blend of luxury, culture, and adventure. " .
                      "Includes premium accommodation, guided tours, and authentic local experiences tailored just for you.";
        
        $stmtPkg->execute([
            $catId, $locId, $p['title'], $pkgSlug, $p['short_desc'],
            $description, $p['price'], $p['duration'], $p['featured']
        ]);

        $pkgId = $pdo->lastInsertId();
        if (!$pkgId) {
            $pkgId = $pdo->query("SELECT id FROM packages WHERE slug = " . $pdo->quote($pkgSlug))->fetchColumn();
        }

        if ($pkgId) {
            // --- 6. SEED ITINERARIES ---
            $stmtItin = $pdo->prepare("INSERT IGNORE INTO itineraries (package_id, day_number, title, description, meals, accommodation) VALUES (?, ?, ?, ?, ?, ?)");
            for ($d = 1; $d <= min($p['duration'], 5); $d++) {
                $meals = ($d % 2 == 0) ? 'breakfast,lunch,dinner' : 'breakfast,dinner';
                $stmtItin->execute([
                    $pkgId, $d, 
                    "Day $d: " . ($d == 1 ? "Arrival & Welcome" : ($d == $p['duration'] ? "Departure" : "Deep Exploration")), 
                    "Detailed activities for Day $d of your amazing journey. Exploring hidden gems and local secrets.",
                    $meals,
                    "Premium Boutique Hotel"
                ]);
            }

            // --- 7. SEED INCLUSIONS ---
            $stmtIncl = $pdo->prepare("INSERT IGNORE INTO package_inclusions (package_id, type, item) VALUES (?, ?, ?)");
            $inclusions = ["Airport Transfer", "Daily Breakfast", "Guided City Tour", "Personal Travel Assistant"];
            foreach ($inclusions as $item) {
                $stmtIncl->execute([$pkgId, 'inclusion', $item]);
            }
            $exclusions = ["Lunch & Dinner (unless specified)", "Personal Expenses", "Insurance", "Tipping"];
            foreach ($exclusions as $item) {
                $stmtIncl->execute([$pkgId, 'exclusion', $item]);
            }
        }
    }
    logMsg("  - Packages, Itineraries, and Inclusions seeded.");

    // --- 8. SEED BOOKINGS & REVIEWS ---
    logMsg("Seeding Bookings & Reviews...");
    $stmtBook = $pdo->prepare("INSERT IGNORE INTO bookings (booking_ref, user_id, package_id, agent_id, travel_date, persons, total_price, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmtRev = $pdo->prepare("INSERT IGNORE INTO reviews (user_id, package_id, booking_id, rating, title, body, status) VALUES (?, ?, ?, ?, ?, ?, ?)");
    
    $allUserIds = $pdo->query("SELECT id FROM users")->fetchAll(PDO::FETCH_COLUMN);
    $allPkgIds = $pdo->query("SELECT id, price FROM packages")->fetchAll();
    $allAgentIds = $pdo->query("SELECT id FROM agents")->fetchAll(PDO::FETCH_COLUMN);

    if (!empty($allUserIds) && !empty($allPkgIds)) {
        foreach (range(1, 10) as $i) {
            $uId = $allUserIds[array_rand($allUserIds)];
            $pkg = $allPkgIds[array_rand($allPkgIds)];
            $agId = !empty($allAgentIds) ? $allAgentIds[array_rand($allAgentIds)] : null;
            
            $ref = "BK-" . strtoupper(substr(uniqid(), -8));
            $date = date('Y-m-d', strtotime('+' . rand(1, 60) . ' days'));
            $persons = rand(1, 4);
            $total = $pkg['price'] * $persons;
            $statuses = ['pending', 'confirmed', 'completed', 'cancelled'];
            $status = $statuses[array_rand($statuses)];

            $stmtBook->execute([$ref, $uId, $pkg['id'], $agId, $date, $persons, $total, $status]);
            $bookId = $pdo->lastInsertId();

            if ($bookId && ($status == 'completed' || $status == 'confirmed')) {
                $rating = rand(4, 5);
                $stmtRev->execute([
                    $uId, $pkg['id'], $bookId, $rating, 
                    "Amazing experience!", 
                    "The " . $pkg['id'] . " trip was absolutely wonderful. Highly recommended!", 
                    'approved'
                ]);
            }
        }
    }
    logMsg("  - Bookings and Reviews seeded.");

    // --- 9. SEED SETTINGS (If empty) ---
    $settingCount = $pdo->query("SELECT COUNT(*) FROM settings")->fetchColumn();
    if ($settingCount == 0) {
        logMsg("Seeding Settings...");
        $pdo->exec("INSERT INTO settings (setting_key, setting_value) VALUES 
            ('site_name', 'Voyara Travels'),
            ('site_email', 'hello@voyara.com'),
            ('site_phone', '+977-1-4000000'),
            ('currency_symbol', 'Rs.'),
            ('currency_code', 'NPR')");
        logMsg("  - Settings seeded.");
    }

    logMsg("Seeding process completed successfully!", 'info');

} catch (PDOException $e) {
    logMsg("Database Error: " . $e->getMessage(), 'error');
    exit(1);
} catch (Exception $e) {
    logMsg("Error: " . $e->getMessage(), 'error');
    exit(1);
}
