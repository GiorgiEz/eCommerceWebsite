<?php

declare(strict_types=1);

namespace App\Database\Seeder\TableSeeders;

use App\Database\Seeder\AbstractSeeder;
use PDO;

/**
 * AttributeItem Seeder class. Inserts data in ATTRIBUTE_ITEMS table
 */
class AttributeItemSeeder extends AbstractSeeder
{
    /**
     * Extracts attribute_items specific data, creates sql statement and inserts the data
     *
     * @param  PDO $pdo Database connection object
     * @param  array $data JSON data, given as array
     *
     * @return void Executes the table insertion logic for ATTRIBUTE_ITEMS table
     */
    protected function run(PDO $pdo, array $data): void
    {
        $products = $data['data']['products'] ?? [];

        $attributes_stmt = $pdo->query('SELECT ATTRIBUTE_EXTERNAL_ID, ATTRIBUTE_ID FROM ATTRIBUTES');
        $attributeMap = $attributes_stmt->fetchAll(PDO::FETCH_KEY_PAIR);

        $seenItems = [];

        $stmt = $pdo->prepare(
            'INSERT IGNORE INTO ATTRIBUTE_ITEMS (ATTRIBUTE_ITEM_ATTRIBUTE_ID, ATTRIBUTE_ITEM_EXTERNAL_ID, 
                ATTRIBUTE_ITEM_DISPLAY_VALUE, ATTRIBUTE_ITEM_VALUE)
                VALUES (:attribute_id, :external_id, :display_value, :item_value)'
        );

        foreach ($products as $product) {
            foreach ($product['attributes'] ?? [] as $attribute) {
                $attributeId = $attributeMap[$attribute['id']] ?? null;

                if ($attributeId === null) {
                    continue;
                }

                foreach ($attribute['items'] ?? [] as $item) {
                    $itemExternalId = $item['id'] ?? null;
                    $itemDisplayValue = $item['displayValue'] ?? null;
                    $itemValue = $item['value'] ?? null;

                    if (!is_string($itemExternalId) || trim($itemExternalId) === '' ||
                        !is_string($itemDisplayValue) || trim($itemDisplayValue) === '' ||
                        !is_string($itemValue) || trim($itemValue) === '' ||
                        isset($seenItems[$attributeId][$itemExternalId])
                    ) {
                        continue;
                    }

                    $stmt->execute([
                        ':attribute_id' => $attributeId,
                        ':external_id' => $itemExternalId,
                        ':display_value' => $itemDisplayValue,
                        ':item_value' => $itemValue,
                    ]);

                    $seenItems[$attributeId][$itemExternalId] = true;
                }
            }
        }
    }
}