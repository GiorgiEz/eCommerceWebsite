<?php

declare(strict_types=1);

namespace App\Database\Seeder\TableSeeders;

use App\Database\Seeder\AbstractSeeder;
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
        $seenLabels = [];

        $currencyInsertStmt = $pdo->prepare(
            'INSERT IGNORE INTO CURRENCIES (CURRENCY_LABEL, CURRENCY_SYMBOL) 
            VALUES (:label, :symbol)'
        );

        foreach($products as $product) {
            if (!$this->isValidProduct($product) || empty($product['prices'])) {
                continue;
            }

            foreach ($product['prices'] as $price) {
                if (!$this->isValidPrice($price)) {
                    continue;
                }

                $label = $price['currency']['label'];

                if (isset($seenLabels[$label])) {
                    continue;
                }

                $currencyInsertStmt->execute([
                    ':label' => $label,
                    ':symbol' => $price['currency']['symbol']
                ]);

                $seenLabels[$label] = true;
            }
        }
    }
}