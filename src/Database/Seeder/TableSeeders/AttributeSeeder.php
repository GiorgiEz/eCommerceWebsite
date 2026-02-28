<?php

declare(strict_types=1);

namespace App\Database\Seeder\TableSeeders;

use App\Database\Seeder\AbstractSeeder;
use PDO;

/**
 * Attribute Seeder class. Inserts data in ATTRIBUTES table
 */
class AttributeSeeder extends AbstractSeeder
{
    /**
     * Extracts attributes specific data, creates sql statement and inserts the data
     *
     * @param  PDO $pdo Database connection object
     * @param  array $data JSON data, given as array
     *
     * @return void Executes the table insertion logic for ATTRIBUTES table
     */
    protected function run(PDO $pdo, array $data): void
    {
        $products = $data['data']['products'] ?? [];
        $seenAttributes = [];

        $attributeInsertStmt = $pdo->prepare(
            'INSERT IGNORE INTO ATTRIBUTES (ATTRIBUTE_NAME, ATTRIBUTE_TYPE, ATTRIBUTE_EXTERNAL_ID)
                VALUES (:attribute_name, :attribute_type, :attribute_external_id)'
        );

        foreach ($products as $product) {
            if (!$this->isValidProduct($product) || empty($product['attributes'])) {
                continue;
            }

            foreach ($product['attributes'] as $attribute) {
                if (!$this->isValidAttribute($attribute)) {
                    continue;
                }

                $attributeExternalId = $attribute['id'];

                if (isset($seenAttributes[$attributeExternalId])) {
                    continue;
                }

                $attributeInsertStmt->execute([
                    ':attribute_name'   => $attribute['name'],
                    ':attribute_type'   => $attribute['type'],
                    ':attribute_external_id' => $attributeExternalId,
                ]);

                $seenAttributes[$attributeExternalId] = true;
            }
        }
    }
}