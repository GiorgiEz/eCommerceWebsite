<?php

declare(strict_types=1);

namespace App\Database\Seeder;

use PDO;

/**
 * Price Seeder class. Inserts data in PRICES table
 */
class PriceSeeder extends AbstractSeeder
{
    /**
     * Extracts prices specific data, creates sql statement and inserts the data
     *
     * @param  PDO $pdo Database connection object
     * @param  array $data JSON data, given as array
     *
     * @return void Executes the table insertion logic for PRICES table
     */
    protected function run(PDO $pdo, array $data): void
    {
        $products = $data['data']['products'] ?? [];

        $productStmt = $pdo->query('SELECT PRODUCT_EXTERNAL_ID, PRODUCT_ID FROM PRODUCTS');
        $productMap = $productStmt->fetchAll(PDO::FETCH_KEY_PAIR);

        $currencyStmt = $pdo->query('SELECT CURRENCY_LABEL, CURRENCY_ID FROM CURRENCIES');
        $currencyMap = $currencyStmt->fetchAll(PDO::FETCH_KEY_PAIR);

        $stmt = $pdo->prepare(
            'INSERT IGNORE INTO PRICES (PRICE_PRODUCT_ID, PRICE_CURRENCY_ID, PRICE_AMOUNT)
                    VALUES (:product_id, :currency_id, :amount)'
        );

        foreach ($products as $product) {
            $externalId = $product['id'] ?? null;

            if (!is_string($externalId) || trim($externalId) === '' ||
                !isset($productMap[$externalId])) {
                continue;
            }

            $productId = $productMap[$externalId];

            foreach ($product['prices'] ?? [] as $price) {
                $amount = $price['amount'] ?? null;
                $currencyLabel = $price['currency']['label'] ?? null;

                if (!is_numeric($amount) ||
                    !is_string($currencyLabel) || trim($currencyLabel) === '' ||
                    !isset($currencyMap[$currencyLabel])) {
                    continue;
                }

                $currencyId = $currencyMap[$currencyLabel];

                $stmt->execute([
                    ':product_id'  => $productId,
                    ':currency_id' => $currencyId,
                    ':amount'      => (float)$amount
                ]);
            }
        }
    }
}