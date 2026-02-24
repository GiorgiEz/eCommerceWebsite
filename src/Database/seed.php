<?php

declare(strict_types=1);

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Database\Config\Database;
use App\Database\Seeder\CategorySeeder;
use App\Database\Seeder\SeederManager;
use App\Database\Utils\JsonLoader;

try {
    $pdo = Database::connect(__DIR__ . '/../../.env');
    $data = JsonLoader::load(__DIR__ . '/../../resources/provided_data.json');

    $manager = new SeederManager([
        new CategorySeeder(),
    ]);

    $manager->run($pdo, $data);

    echo "Database Insertion completed successfully." . PHP_EOL;

} catch (Throwable $e) {
    echo 'Error: ' . $e->getMessage() . PHP_EOL;
}