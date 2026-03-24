<?php

declare(strict_types=1);

# Ensure the script is executed from the command line only
if (php_sapi_name() !== 'cli') {
    exit('This script must be run from the CLI.' . PHP_EOL);
}

require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

use App\Database\Config\Database;
use App\Database\Utils\JsonLoader;
use App\Database\Seeder\SeederManager;
use App\Database\Seeder\TableSeeders\{
    CategorySeeder,
    CurrencySeeder,
    ProductSeeder,
    PriceSeeder,
    ImageSeeder,
    AttributeSeeder,
    AttributeItemSeeder,
    ProductAttributeItemSeeder,
};

try {
    // Establish database connection
    $pdo = Database::connect();

    // Load seed data from JSON file
    $data = JsonLoader::load(__DIR__ . '/../resources/provided_data.json');

    // Register all table-specific seeders
    $manager = new SeederManager([
        new CategorySeeder(),
        new CurrencySeeder(),
        new ProductSeeder(),
        new PriceSeeder(),
        new ImageSeeder(),
        new AttributeSeeder(),
        new AttributeItemSeeder(),
        new ProductAttributeItemSeeder(),
    ]);

    // Execute database seeding
    $manager->run($pdo, $data);

    echo 'Database insertion completed successfully.' . PHP_EOL;

} catch (Throwable $exception) {
    // Catch any runtime, database, or seeding errors
    echo 'Error: ' . $exception->getMessage() . PHP_EOL;
    exit(1);
}