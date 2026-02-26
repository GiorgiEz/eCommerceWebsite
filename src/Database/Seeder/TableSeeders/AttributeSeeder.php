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

        $stmt = $pdo->prepare(
            'INSERT IGNORE INTO ATTRIBUTES (ATTRIBUTE_NAME, ATTRIBUTE_TYPE, ATTRIBUTE_EXTERNAL_ID)
                VALUES (:attribute_name, :attribute_type, :attribute_external_id)'
        );

        $seen_attributes = [];

        foreach ($products as $product) {
            foreach ($product['attributes'] ?? [] as $attribute) {
                $attributeExternalId = $attribute['id'] ?? null;
                $attributeType = $attribute['type'] ?? null;
                $attributeName = $attribute['name'] ?? null;

                if (!is_string($attributeExternalId) || trim($attributeExternalId) === '' ||
                    !is_string($attributeType) || trim($attributeType) === '' ||
                    !is_string($attributeName) || trim($attributeName) === '' ||
                    isset($seen_attributes[$attributeExternalId])
                ) {
                    continue;
                }

                $stmt->execute([
                    ':attribute_name'   => $attributeName,
                    ':attribute_type'   => $attributeType,
                    ':attribute_external_id' => $attributeExternalId,
                ]);

                $seen_attributes[$attributeExternalId] = true;
            }
        }
    }
}