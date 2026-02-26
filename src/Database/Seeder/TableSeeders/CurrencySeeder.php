<?php

declare(strict_types=1);

namespace App\Database\Seeder;

use PDO;

/**
 * Currency Seeder class. Inserts data in CURRENCIES table
 */
class CurrencySeeder extends AbstractSeeder
{
    /**
     * Extracts currencies specific data, creates sql statement and inserts the data
     *
     * @param  PDO $pdo Database connection object
     * @param  array $data JSON data, given as array
     *
     * @return void Executes the table insertion logic for CURRENCIES table
     */
    protected function run(PDO $pdo, array $data): void
    {
        $products = $data['data']['products'] ?? [];
        $seen_labels = [];

        $stmt = $pdo->prepare(
            'INSERT IGNORE INTO CURRENCIES (CURRENCY_LABEL, CURRENCY_SYMBOL) VALUES (:label, :symbol)'
        );

        foreach($products as $product) {
            $prices = $product['prices'] ?? [];

            foreach ($prices as $price) {
                $label = $price['currency']['label'] ?? null;
                $symbol = $price['currency']['symbol'] ?? null;

                if ($label === null || trim($label) === '' || $symbol === null || trim($symbol) === '') {
                    continue;
                }

                if (!isset($seen_labels[$label])) {
                    $stmt->execute([
                        ':label' => $label,
                        ':symbol' => $symbol
                    ]);

                    $seen_labels[$label] = true;
                }
            }
        }
    }
}