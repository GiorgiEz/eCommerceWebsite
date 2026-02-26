<?php

declare(strict_types=1);

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Database\Config\Database;
use App\Database\Utils\JsonLoader;
use App\Database\Seeder\SeederManager;

use App\Database\Seeder\TableSeeders\CategorySeeder;
use App\Database\Seeder\TableSeeders\CurrencySeeder;
use App\Database\Seeder\TableSeeders\PriceSeeder;
use App\Database\Seeder\TableSeeders\ProductSeeder;
use App\Database\Seeder\TableSeeders\ImageSeeder;
use App\Database\Seeder\TableSeeders\AttributeSeeder;
use App\Database\Seeder\TableSeeders\AttributeItemSeeder;

try {
    $pdo = Database::connect(__DIR__ . '/../../.env');
    $data = JsonLoader::load(__DIR__ . '/../../resources/provided_data.json');

    $manager = new SeederManager([
        new CategorySeeder(),
        new CurrencySeeder(),
        new ProductSeeder(),
        new PriceSeeder(),
        new ImageSeeder(),
        new AttributeSeeder(),
        new AttributeItemSeeder(),
    ]);

    $manager->run($pdo, $data);

    echo "Database Insertion completed successfully." . PHP_EOL;

} catch (Throwable $e) {
    echo 'Error: ' . $e->getMessage() . PHP_EOL;
}