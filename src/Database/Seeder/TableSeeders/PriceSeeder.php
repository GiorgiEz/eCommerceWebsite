<?php

declare(strict_types=1);

namespace App\Database\Seeder\TableSeeders;

use App\Database\Seeder\AbstractSeeder;
use PDO;

/**
 * Class PriceSeeder
 *
 * Seeds the PRICES table using price data from the dataset.
 */
class PriceSeeder extends AbstractSeeder
{
    /**
     * Inserts price records into the PRICES table.
     *
     * @param PDO   $pdo  Active database connection
     * @param array $data Seed data loaded from JSON
     *
     * @return void
     */
    protected function run(PDO $pdo, array $data): void
    {
        $products = $data['data']['products'] ?? [];

        $productStmt = $pdo->query('SELECT PRODUCT_EXTERNAL_ID, PRODUCT_ID FROM PRODUCTS');
        $productMap = $productStmt->fetchAll(PDO::FETCH_KEY_PAIR);

        $currencyStmt = $pdo->query('SELECT CURRENCY_LABEL, CURRENCY_ID FROM CURRENCIES');
        $currencyMap = $currencyStmt->fetchAll(PDO::FETCH_KEY_PAIR);

        $priceInsertStmt = $pdo->prepare(
            'INSERT IGNORE INTO PRICES (PRICE_PRODUCT_ID, PRICE_CURRENCY_ID, PRICE_AMOUNT)
                    VALUES (:product_id, :currency_id, :amount)'
        );

        foreach ($products as $product) {
            if (!$this->isValidProduct($product) || !isset($productMap[$product['id']]) || empty($product['prices'])) {
                continue;
            }

            foreach ($product['prices'] as $price) {
                if (!$this->isValidPrice($price)) {
                    continue;
                }

                $currencyLabel = $price['currency']['label'];

                if (!isset($currencyMap[$currencyLabel])) {
                    continue;
                }

                $priceInsertStmt->execute([
                    ':product_id'  => $productMap[$product['id']],
                    ':currency_id' => $currencyMap[$currencyLabel],
                    ':amount'      => round((float)$price['amount'], 2)
                ]);
            }
        }
    }
}