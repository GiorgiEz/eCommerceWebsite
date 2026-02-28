<?php

declare(strict_types=1);

namespace App\Database\Seeder\TableSeeders;

use App\Database\Seeder\AbstractSeeder;
use PDO;

/**
 * Product Seeder class. Inserts data in PRODUCTS table
 */
class ProductSeeder extends AbstractSeeder
{
    /**
     * Extracts products specific data, creates sql statement and inserts the data
     *
     * @param  PDO $pdo Database connection object
     * @param  array $data JSON data, given as array
     *
     * @return void Executes the table insertion logic for PRODUCTS table
     */
    protected function run(PDO $pdo, array $data): void
    {
        $products = $data['data']['products'] ?? [];

        $categoryStmt = $pdo->query('SELECT CATEGORY_NAME, CATEGORY_ID FROM CATEGORIES');
        $categoryMap = $categoryStmt->fetchAll(PDO::FETCH_KEY_PAIR);

        $productInsertStmt = $pdo->prepare('
            INSERT IGNORE INTO PRODUCTS 
                (PRODUCT_CATEGORY_ID, PRODUCT_NAME, PRODUCT_DESCRIPTION, PRODUCT_BRAND, 
                    PRODUCT_IN_STOCK, PRODUCT_EXTERNAL_ID) 
            VALUES (:category_id, :name, :description, :brand, :inStock, :external_id)');

        foreach ($products as $product) {
            if (!$this->isValidProduct($product) || !isset($categoryMap[$product['category']])) {
                continue;
            }

            $productInsertStmt->execute([
                ':name' => $product['name'],
                ':category_id' => $categoryMap[$product['category']],
                ':inStock' => (int) $product['inStock'],
                ':external_id' => $product['id'],
                ':description' => $product['description'],
                ':brand' => $product['brand'],
            ]);
        }
    }
}