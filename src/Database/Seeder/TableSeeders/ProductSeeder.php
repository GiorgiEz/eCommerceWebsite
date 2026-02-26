<?php

declare(strict_types=1);

namespace App\Database\Seeder;

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
        $categories_stmt = $pdo->query('SELECT CATEGORY_NAME, CATEGORY_ID FROM CATEGORIES');

        $categoryMap = $categories_stmt->fetchAll(PDO::FETCH_KEY_PAIR);
        $stmt = $pdo->prepare('
            INSERT IGNORE INTO PRODUCTS 
                (PRODUCT_CATEGORY_ID, PRODUCT_NAME, PRODUCT_DESCRIPTION, PRODUCT_BRAND, 
                    PRODUCT_IN_STOCK, PRODUCT_EXTERNAL_ID) 
            VALUES (:category_id, :name, :description, :brand, :inStock, :external_id)');

        foreach ($products as $product) {
            $categoryName = $product['category'] ?? null;
            $productName = $product['name'] ?? null;
            $description = $product['description'] ?? null;
            $brand = $product['brand'] ?? null;
            $inStock = isset($product['inStock']) ? (int)$product['inStock'] : null;
            $externalId = $product['id'] ?? null;

            if ($productName === null || trim($productName) === '' ||
                $categoryName === null || trim($categoryName) === '' ||
                $brand === null || trim($brand) === '' ||
                $inStock === null || $externalId === null || trim($externalId) === '' ||
                !isset($categoryMap[$categoryName])) {
                continue;
            }

            $stmt->execute([
                ':name' => $productName,
                ':category_id' => $categoryMap[$categoryName],
                ':inStock' => $inStock,
                ':external_id' => $externalId,
                ':description' => $description,
                ':brand' => $brand,
            ]);
        }
    }
}