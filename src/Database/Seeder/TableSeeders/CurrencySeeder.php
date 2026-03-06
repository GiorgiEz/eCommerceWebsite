<?php

declare(strict_types=1);

namespace App\Database\Seeder\TableSeeders;

use App\Database\Seeder\AbstractSeeder;
use PDO;

/**
 * Class CurrencySeeder
 *
 * Seeds the CURRENCIES table using currency data from the dataset.
 */
class CurrencySeeder extends AbstractSeeder
{
    /**
     * Inserts currency records into the CURRENCIES table.
     *
     * @param PDO   $pdo  Active database connection
     * @param array $data Seed data loaded from JSON
     *
     * @return void
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