<?php

declare(strict_types=1);

namespace App\Database\Seeder\TableSeeders;

use App\Database\Seeder\AbstractSeeder;
use PDO;

/**
 * Image Seeder class. Inserts data in IMAGES table
 */
class ImageSeeder extends AbstractSeeder
{
    /**
     * Extracts images specific data, creates sql statement and inserts the data
     *
     * @param  PDO $pdo Database connection object
     * @param  array $data JSON data, given as array
     *
     * @return void Executes the table insertion logic for IMAGES table
     */
    protected function run(PDO $pdo, array $data): void
    {
        $products = $data['data']['products'] ?? [];

        $productStmt = $pdo->query('SELECT PRODUCT_EXTERNAL_ID, PRODUCT_ID FROM PRODUCTS');
        $productMap = $productStmt->fetchAll(PDO::FETCH_KEY_PAIR);

        $imageInsertStmt = $pdo->prepare(
            'INSERT IGNORE INTO IMAGES (IMAGE_PRODUCT_ID, IMAGE_URL)
            VALUES (:product_id, :image_url)'
        );

        foreach ($products as $product) {
            if (!$this->isValidProduct($product) || !isset($productMap[$product['id']]) || empty($product['gallery'])) {
                continue;
            }

            foreach ($product['gallery'] as $imageUrl) {
                if (!is_string($imageUrl) || trim($imageUrl) === '') {
                    continue;
                }

                $imageInsertStmt->execute([
                    ':product_id'  => $productMap[$product['id']],
                    ':image_url'   => $imageUrl
                ]);
            }
        }
    }
}