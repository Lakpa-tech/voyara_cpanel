-- Author:  Kiran Khadka
-- Version: 1.0.0 (First edition)
-- Contact: +977-9869756622
-- Mail:    therealkiranda@gmail.com
-- © 2026 Kiran Khadka. All rights reserved.

SET FOREIGN_KEY_CHECKS = 0;
SET SQL_MODE = 'STRICT_TRANS_TABLES,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO';

CREATE DATABASE IF NOT EXISTS voyara_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE voyara_db;

-- -------------------------------------------------------
-- USERS
-- -------------------------------------------------------
CREATE TABLE users (
    id           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name         VARCHAR(150) NOT NULL,
    email        VARCHAR(200) NOT NULL UNIQUE,
    phone        VARCHAR(20),
    password     VARCHAR(255) NOT NULL,
    avatar       VARCHAR(255) DEFAULT NULL,
    is_active    TINYINT(1) NOT NULL DEFAULT 1,
    email_verified_at DATETIME DEFAULT NULL,
    created_at   DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at   DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_active (is_active)
) ENGINE=InnoDB;

-- -------------------------------------------------------
-- ADMINS
-- -------------------------------------------------------
CREATE TABLE admins (
    id           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name         VARCHAR(150) NOT NULL,
    email        VARCHAR(200) NOT NULL UNIQUE,
    password     VARCHAR(255) NOT NULL,
    is_active    TINYINT(1) NOT NULL DEFAULT 1,
    last_login   DATETIME DEFAULT NULL,
    created_at   DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at   DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email)
) ENGINE=InnoDB;

-- -------------------------------------------------------
-- AGENTS
-- -------------------------------------------------------
CREATE TABLE agents (
    id           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name         VARCHAR(150) NOT NULL,
    email        VARCHAR(200) NOT NULL UNIQUE,
    phone        VARCHAR(20),
    password     VARCHAR(255) NOT NULL,
    bio          TEXT,
    is_active    TINYINT(1) NOT NULL DEFAULT 1,
    last_login   DATETIME DEFAULT NULL,
    created_at   DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at   DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email)
) ENGINE=InnoDB;

-- -------------------------------------------------------
-- CATEGORIES
-- -------------------------------------------------------
CREATE TABLE categories (
    id           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name         VARCHAR(100) NOT NULL,
    slug         VARCHAR(120) NOT NULL UNIQUE,
    icon         VARCHAR(60) DEFAULT NULL,
    created_at   DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- -------------------------------------------------------
-- LOCATIONS
-- -------------------------------------------------------
CREATE TABLE locations (
    id           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name         VARCHAR(150) NOT NULL,
    country      VARCHAR(100) NOT NULL,
    slug         VARCHAR(180) NOT NULL UNIQUE,
    created_at   DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_country (country)
) ENGINE=InnoDB;

-- -------------------------------------------------------
-- PACKAGES
-- -------------------------------------------------------
CREATE TABLE packages (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    category_id     INT UNSIGNED NOT NULL,
    location_id     INT UNSIGNED NOT NULL,
    title           VARCHAR(250) NOT NULL,
    slug            VARCHAR(280) NOT NULL UNIQUE,
    short_desc      VARCHAR(400),
    description     LONGTEXT,
    price           DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    price_per       ENUM('person','group') NOT NULL DEFAULT 'person',
    duration_days   SMALLINT UNSIGNED NOT NULL DEFAULT 1,
    max_persons     SMALLINT UNSIGNED DEFAULT NULL,
    cover_image     VARCHAR(255) DEFAULT NULL,
    is_featured     TINYINT(1) NOT NULL DEFAULT 0,
    is_active       TINYINT(1) NOT NULL DEFAULT 1,
    view_count      INT UNSIGNED NOT NULL DEFAULT 0,
    created_at      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE RESTRICT,
    FOREIGN KEY (location_id) REFERENCES locations(id) ON DELETE RESTRICT,
    INDEX idx_active_featured (is_active, is_featured),
    INDEX idx_price (price),
    INDEX idx_duration (duration_days),
    FULLTEXT idx_search (title, short_desc, description)
) ENGINE=InnoDB;

-- -------------------------------------------------------
-- PACKAGE IMAGES
-- -------------------------------------------------------
CREATE TABLE package_images (
    id           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    package_id   INT UNSIGNED NOT NULL,
    image_path   VARCHAR(255) NOT NULL,
    sort_order   TINYINT UNSIGNED NOT NULL DEFAULT 0,
    FOREIGN KEY (package_id) REFERENCES packages(id) ON DELETE CASCADE,
    INDEX idx_package (package_id)
) ENGINE=InnoDB;

-- -------------------------------------------------------
-- ITINERARIES (day-wise)
-- -------------------------------------------------------
CREATE TABLE itineraries (
    id           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    package_id   INT UNSIGNED NOT NULL,
    day_number   TINYINT UNSIGNED NOT NULL,
    title        VARCHAR(200) NOT NULL,
    description  TEXT NOT NULL,
    meals        SET('breakfast','lunch','dinner') DEFAULT NULL,
    accommodation VARCHAR(200) DEFAULT NULL,
    created_at   DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (package_id) REFERENCES packages(id) ON DELETE CASCADE,
    UNIQUE KEY uq_package_day (package_id, day_number),
    INDEX idx_package (package_id)
) ENGINE=InnoDB;

-- -------------------------------------------------------
-- PACKAGE INCLUSIONS / EXCLUSIONS
-- -------------------------------------------------------
CREATE TABLE package_inclusions (
    id           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    package_id   INT UNSIGNED NOT NULL,
    type         ENUM('inclusion','exclusion') NOT NULL DEFAULT 'inclusion',
    item         VARCHAR(300) NOT NULL,
    FOREIGN KEY (package_id) REFERENCES packages(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- -------------------------------------------------------
-- BOOKINGS
-- -------------------------------------------------------
CREATE TABLE bookings (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    booking_ref     VARCHAR(20) NOT NULL UNIQUE,
    user_id         INT UNSIGNED NOT NULL,
    package_id      INT UNSIGNED NOT NULL,
    agent_id        INT UNSIGNED DEFAULT NULL,
    travel_date     DATE NOT NULL,
    persons         SMALLINT UNSIGNED NOT NULL DEFAULT 1,
    total_price     DECIMAL(10,2) NOT NULL,
    status          ENUM('pending','confirmed','completed','cancelled') NOT NULL DEFAULT 'pending',
    special_requests TEXT DEFAULT NULL,
    admin_notes     TEXT DEFAULT NULL,
    created_at      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id)    REFERENCES users(id)    ON DELETE RESTRICT,
    FOREIGN KEY (package_id) REFERENCES packages(id) ON DELETE RESTRICT,
    FOREIGN KEY (agent_id)   REFERENCES agents(id)   ON DELETE SET NULL,
    INDEX idx_user    (user_id),
    INDEX idx_package (package_id),
    INDEX idx_status  (status),
    INDEX idx_date    (travel_date)
) ENGINE=InnoDB;

-- -------------------------------------------------------
-- PAYMENTS
-- -------------------------------------------------------
CREATE TABLE payments (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    booking_id      INT UNSIGNED NOT NULL UNIQUE,
    amount          DECIMAL(10,2) NOT NULL,
    method          ENUM('bank_transfer','cash','other') NOT NULL DEFAULT 'bank_transfer',
    transaction_ref VARCHAR(100) DEFAULT NULL,
    receipt_path    VARCHAR(255) DEFAULT NULL,
    status          ENUM('pending','verified','rejected') NOT NULL DEFAULT 'pending',
    verified_by     INT UNSIGNED DEFAULT NULL,
    verified_at     DATETIME DEFAULT NULL,
    notes           TEXT DEFAULT NULL,
    created_at      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (booking_id)   REFERENCES bookings(id) ON DELETE CASCADE,
    FOREIGN KEY (verified_by)  REFERENCES admins(id)   ON DELETE SET NULL,
    INDEX idx_booking (booking_id),
    INDEX idx_status  (status)
) ENGINE=InnoDB;

-- -------------------------------------------------------
-- REVIEWS
-- -------------------------------------------------------
CREATE TABLE reviews (
    id           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id      INT UNSIGNED NOT NULL,
    package_id   INT UNSIGNED NOT NULL,
    booking_id   INT UNSIGNED NOT NULL,
    rating       TINYINT UNSIGNED NOT NULL CHECK (rating BETWEEN 1 AND 5),
    title        VARCHAR(200) DEFAULT NULL,
    body         TEXT NOT NULL,
    status       ENUM('pending','approved','rejected') NOT NULL DEFAULT 'pending',
    created_at   DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id)    REFERENCES users(id)    ON DELETE CASCADE,
    FOREIGN KEY (package_id) REFERENCES packages(id) ON DELETE CASCADE,
    FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE CASCADE,
    UNIQUE KEY uq_booking_review (booking_id),
    INDEX idx_package_status (package_id, status)
) ENGINE=InnoDB;

-- -------------------------------------------------------
-- CSRF TOKENS (server-side storage)
-- -------------------------------------------------------
CREATE TABLE csrf_tokens (
    token        VARCHAR(64) NOT NULL PRIMARY KEY,
    session_id   VARCHAR(128) NOT NULL,
    created_at   DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_session (session_id)
) ENGINE=InnoDB;

-- -------------------------------------------------------
-- SITE SETTINGS
-- -------------------------------------------------------
CREATE TABLE settings (
    setting_key   VARCHAR(80) NOT NULL PRIMARY KEY,
    setting_value TEXT,
    updated_at    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

SET FOREIGN_KEY_CHECKS = 1;

-- -------------------------------------------------------
-- SEED DATA
-- -------------------------------------------------------

-- Default admin (password: Admin@123)
INSERT INTO admins (name, email, password) VALUES
('Super Admin', 'admin@voyara.com', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uXbxDKJKW');

-- Categories
INSERT INTO categories (name, slug, icon) VALUES
('Domestic',      'domestic',      'bi-house-door'),
('International', 'international', 'bi-globe'),
('Adventure',     'adventure',     'bi-mountain'),
('Beach',         'beach',         'bi-water'),
('Cultural',      'cultural',      'bi-building'),
('Honeymoon',     'honeymoon',     'bi-heart');

-- Locations
INSERT INTO locations (name, country, slug) VALUES
('Bali',            'Indonesia',    'bali-indonesia'),
('Santorini',       'Greece',       'santorini-greece'),
('Kyoto',           'Japan',        'kyoto-japan'),
('Maldives',        'Maldives',     'maldives'),
('Amalfi Coast',    'Italy',        'amalfi-coast-italy'),
('Patagonia',       'Argentina',    'patagonia-argentina'),
('Goa',             'India',        'goa-india'),
('Dubai',           'UAE',          'dubai-uae');

-- Site settings
INSERT INTO settings (setting_key, setting_value) VALUES
('site_name',          'Voyara Travel'),
('site_email',         'info@voyara.com'),
('site_phone',         '+1 800 VOYARA'),
('bank_name',          'Global Travel Bank'),
('bank_account',       '1234567890'),
('bank_routing',       '021000021'),
('bank_account_name',  'Voyara Travel Agency LLC'),
('bank_swift',         'GLTBUS33'),
('currency_symbol',    '$'),
('currency_code',      'USD'),
('booking_fee_pct',    '0');
