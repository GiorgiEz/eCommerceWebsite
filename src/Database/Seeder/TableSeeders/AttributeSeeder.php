<?php

declare(strict_types=1);

namespace App\Database\Seeder\TableSeeders;

use App\Database\Seeder\AbstractSeeder;
use PDO;

/**
 * Class AttributeSeeder
 *
 * Seeds the ATTRIBUTES table using attribute data from the dataset.
 */
class AttributeSeeder extends AbstractSeeder
{
    /**
     * Inserts attribute records into the ATTRIBUTES table.
     *
     * @param PDO   $pdo  Active database connection
     * @param array $data Seed data loaded from JSON
     *
     * @return void
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