<?php
/**
 * Author:  Kiran Khadka
 * Version: 1.0.0 (First edition)
 * Contact: +977-9869756622
 * Mail:    therealkiranda@gmail.com
 * © 2026 Kiran Khadka. All rights reserved.
 */
class PackageModel {
    public static function all(array $filters = [], int $limit = 12, int $offset = 0): array {
        [$where, $params] = self::buildFilters($filters);
        $sql = "SELECT p.*, c.name AS category_name, l.name AS location_name, l.country,
                       COALESCE(AVG(r.rating), 0) AS avg_rating, COUNT(DISTINCT r.id) AS review_count
                FROM packages p
                JOIN categories c ON c.id = p.category_id
                JOIN locations  l ON l.id = p.location_id
                LEFT JOIN reviews r ON r.package_id = p.id AND r.status = 'approved'
                WHERE p.is_active = 1 {$where}
                GROUP BY p.id
                ORDER BY p.is_featured DESC, p.created_at DESC
                LIMIT ? OFFSET ?";
        $params[] = $limit;
        $params[] = $offset;
        return DB::fetchAll($sql, $params);
    }

    public static function count(array $filters = []): int {
        [$where, $params] = self::buildFilters($filters);
        $sql = "SELECT COUNT(DISTINCT p.id) FROM packages p
                JOIN categories c ON c.id = p.category_id
                JOIN locations  l ON l.id = p.location_id
                WHERE p.is_active = 1 {$where}";
        return (int) DB::fetchColumn($sql, $params);
    }

    private static function buildFilters(array $f): array {
        $where = '';
        $params = [];
        if (!empty($f['keyword'])) {
            $where .= ' AND MATCH(p.title, p.short_desc, p.description) AGAINST(? IN BOOLEAN MODE)';
            $params[] = '+' . str_replace(' ', '* +', trim($f['keyword'])) . '*';
        }
        if (!empty($f['category'])) {
            $where .= ' AND c.slug = ?';
            $params[] = $f['category'];
        }
        if (!empty($f['location'])) {
            $where .= ' AND l.slug = ?';
            $params[] = $f['location'];
        }
        if (!empty($f['min_price'])) {
            $where .= ' AND p.price >= ?';
            $params[] = (float) $f['min_price'];
        }
        if (!empty($f['max_price'])) {
            $where .= ' AND p.price <= ?';
            $params[] = (float) $f['max_price'];
        }
        if (!empty($f['min_days'])) {
            $where .= ' AND p.duration_days >= ?';
            $params[] = (int) $f['min_days'];
        }
        if (!empty($f['max_days'])) {
            $where .= ' AND p.duration_days <= ?';
            $params[] = (int) $f['max_days'];
        }
        return [$where, $params];
    }

    public static function findBySlug(string $slug): ?array {
        $pkg = DB::fetchOne(
            "SELECT p.*, c.name AS category_name, c.slug AS category_slug,
                    l.name AS location_name, l.country, l.slug AS location_slug,
                    COALESCE(AVG(r.rating),0) AS avg_rating, COUNT(DISTINCT r.id) AS review_count
             FROM packages p
             JOIN categories c ON c.id = p.category_id
             JOIN locations  l ON l.id = p.location_id
             LEFT JOIN reviews r ON r.package_id = p.id AND r.status = 'approved'
             WHERE p.slug = ? AND p.is_active = 1
             GROUP BY p.id",
            [$slug]
        );
        if (!$pkg) return null;

        $pkg['itineraries'] = DB::fetchAll(
            'SELECT * FROM itineraries WHERE package_id = ? ORDER BY day_number',
            [$pkg['id']]
        );
        $pkg['images'] = DB::fetchAll(
            'SELECT * FROM package_images WHERE package_id = ? ORDER BY sort_order',
            [$pkg['id']]
        );
        $pkg['inclusions'] = DB::fetchAll(
            "SELECT * FROM package_inclusions WHERE package_id = ? AND type = 'inclusion'",
            [$pkg['id']]
        );
        $pkg['exclusions'] = DB::fetchAll(
            "SELECT * FROM package_inclusions WHERE package_id = ? AND type = 'exclusion'",
            [$pkg['id']]
        );
        return $pkg;
    }

    public static function findById(int $id): ?array {
        return DB::fetchOne('SELECT * FROM packages WHERE id = ?', [$id]);
    }

    public static function featured(int $limit = 6): array {
        return DB::fetchAll(
            "SELECT p.*, c.name AS category_name, l.name AS location_name, l.country,
                    COALESCE(AVG(r.rating),0) AS avg_rating, COUNT(DISTINCT r.id) AS review_count
             FROM packages p
             JOIN categories c ON c.id = p.category_id
             JOIN locations  l ON l.id = p.location_id
             LEFT JOIN reviews r ON r.package_id = p.id AND r.status = 'approved'
             WHERE p.is_active = 1 AND p.is_featured = 1
             GROUP BY p.id
             ORDER BY p.created_at DESC LIMIT ?",
            [$limit]
        );
    }

    public static function create(array $d): int {
        DB::query(
            "INSERT INTO packages (category_id, location_id, title, slug, short_desc, description,
             price, price_per, duration_days, max_persons, cover_image, is_featured, is_active)
             VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)",
            [
                $d['category_id'], $d['location_id'], $d['title'], $d['slug'],
                $d['short_desc'], $d['description'], $d['price'], $d['price_per'],
                $d['duration_days'], $d['max_persons'] ?? null, $d['cover_image'] ?? null,
                $d['is_featured'] ?? 0, $d['is_active'] ?? 1,
            ]
        );
        return (int) DB::lastInsertId();
    }

    public static function update(int $id, array $d): void {
        $sets = []; $params = [];
        $cols = ['category_id','location_id','title','slug','short_desc','description',
                 'price','price_per','duration_days','max_persons','cover_image','is_featured','is_active'];
        foreach ($cols as $col) {
            if (array_key_exists($col, $d)) {
                $sets[]   = "{$col} = ?";
                $params[] = $d[$col];
            }
        }
        $params[] = $id;
        DB::query('UPDATE packages SET ' . implode(', ', $sets) . ' WHERE id = ?', $params);
    }

    public static function delete(int $id): void {
        DB::query('DELETE FROM packages WHERE id = ?', [$id]);
    }

    public static function slugExists(string $slug, int $excludeId = 0): bool {
        $r = DB::fetchOne('SELECT id FROM packages WHERE slug = ? AND id != ?', [$slug, $excludeId]);
        return (bool)$r;
    }

    public static function makeSlug(string $title): string {
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title), '-'));
        $base = $slug; $i = 1;
        while (self::slugExists($slug)) { $slug = $base . '-' . $i++; }
        return $slug;
    }

    public static function saveItineraries(int $packageId, array $days): void {
        DB::query('DELETE FROM itineraries WHERE package_id = ?', [$packageId]);
        foreach ($days as $day) {
            DB::query(
                'INSERT INTO itineraries (package_id, day_number, title, description, meals, accommodation) VALUES (?,?,?,?,?,?)',
                [$packageId, $day['day_number'], $day['title'], $day['description'],
                 $day['meals'] ?? null, $day['accommodation'] ?? null]
            );
        }
    }

    public static function saveInclusions(int $packageId, array $inclusions, array $exclusions): void {
        DB::query('DELETE FROM package_inclusions WHERE package_id = ?', [$packageId]);
        foreach ($inclusions as $item) {
            if (trim($item)) DB::query(
                "INSERT INTO package_inclusions (package_id, type, item) VALUES (?,'inclusion',?)",
                [$packageId, trim($item)]
            );
        }
        foreach ($exclusions as $item) {
            if (trim($item)) DB::query(
                "INSERT INTO package_inclusions (package_id, type, item) VALUES (?,'exclusion',?)",
                [$packageId, trim($item)]
            );
        }
    }

    public static function addImage(int $packageId, string $filename, int $sortOrder = 0): void {
        DB::query(
            'INSERT INTO package_images (package_id, image_path, sort_order) VALUES (?,?,?)',
            [$packageId, $filename, $sortOrder]
        );
    }

    public static function deleteImage(int $imageId): ?string {
        $row = DB::fetchOne('SELECT image_path FROM package_images WHERE id = ?', [$imageId]);
        if ($row) {
            DB::query('DELETE FROM package_images WHERE id = ?', [$imageId]);
            return $row['image_path'];
        }
        return null;
    }

    public static function allForAdmin(int $limit = 50, int $offset = 0): array {
        return DB::fetchAll(
            "SELECT p.*, c.name AS category_name, l.name AS location_name
             FROM packages p
             JOIN categories c ON c.id = p.category_id
             JOIN locations  l ON l.id = p.location_id
             ORDER BY p.created_at DESC LIMIT ? OFFSET ?",
            [$limit, $offset]
        );
    }

    public static function totalCount(): int {
        return (int) DB::fetchColumn('SELECT COUNT(*) FROM packages');
    }

    public static function categories(): array {
        return DB::fetchAll('SELECT * FROM categories ORDER BY name');
    }

    public static function locations(): array {
        return DB::fetchAll('SELECT * FROM locations ORDER BY name');
    }

    public static function incrementViews(int $id): void {
        DB::query('UPDATE packages SET view_count = view_count + 1 WHERE id = ?', [$id]);
    }
}
