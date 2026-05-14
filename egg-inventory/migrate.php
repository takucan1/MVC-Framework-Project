#!/usr/bin/env php
<?php

declare(strict_types=1);

/**
 * Database migration and seed script.
 * Run once: php migrate.php
 */

$dbPath = __DIR__ . '/storage/eggs.sqlite';

$pdo = new PDO("sqlite:{$dbPath}");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Create table
$pdo->exec("
    CREATE TABLE IF NOT EXISTS eggs (
        id          INTEGER PRIMARY KEY AUTOINCREMENT,
        egg_type    TEXT    NOT NULL CHECK(egg_type IN ('quail','white','brown')),
        batch_label TEXT    NOT NULL,
        quantity    INTEGER NOT NULL DEFAULT 0,
        unit_price  REAL    NOT NULL DEFAULT 0.00,
        notes       TEXT    DEFAULT '',
        created_at  TEXT    NOT NULL DEFAULT (datetime('now','localtime'))
    )
");

echo "✅ Table 'eggs' created.\n";

// Seed sample data
$seeds = [
    ['quail', 'Batch #001 — Mountain Farm',   1200, 2.50, 'Organic quail eggs from Benguet'],
    ['quail', 'Batch #002 — Valley Ranch',     800, 2.75, 'Free-range, no antibiotics'],
    ['white', 'Batch #003 — CityFarm Supply', 3000, 3.00, 'Large white commercial eggs'],
    ['white', 'Batch #004 — NorthLay Farms',  2500, 3.25, 'Grade A, refrigerated transit'],
    ['brown', 'Batch #005 — Heritage Coop',    600, 5.00, 'Heritage breed, pasture-raised'],
    ['brown', 'Batch #006 — SunRise Poultry', 1500, 4.50, 'Brown eggs, medium size'],
];

$stmt = $pdo->prepare("
    INSERT INTO eggs (egg_type, batch_label, quantity, unit_price, notes)
    VALUES (:egg_type, :batch_label, :quantity, :unit_price, :notes)
");

foreach ($seeds as [$type, $label, $qty, $price, $notes]) {
    $stmt->execute([
        'egg_type'    => $type,
        'batch_label' => $label,
        'quantity'    => $qty,
        'unit_price'  => $price,
        'notes'       => $notes,
    ]);
}

echo "✅ Seeded " . count($seeds) . " sample batches.\n";
echo "Done! Visit http://localhost:8000/eggs\n";
