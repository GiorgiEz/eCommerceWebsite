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
        $seenAttributeItems = [];

        $attributesStmt = $pdo->query('SELECT ATTRIBUTE_EXTERNAL_ID, ATTRIBUTE_ID FROM ATTRIBUTES');
        $attributeMap = $attributesStmt->fetchAll(PDO::FETCH_KEY_PAIR);

        $attributeItemInsertStmt = $pdo->prepare(
            'INSERT IGNORE INTO ATTRIBUTE_ITEMS (ATTRIBUTE_ITEM_ATTRIBUTE_ID, ATTRIBUTE_ITEM_EXTERNAL_ID, 
                ATTRIBUTE_ITEM_DISPLAY_VALUE, ATTRIBUTE_ITEM_VALUE)
                VALUES (:attribute_id, :external_id, :display_value, :item_value)'
        );

        foreach ($products as $product) {
            if (!$this->isValidProduct($product) || empty($product['attributes'])) {
                continue;
            }

            foreach ($product['attributes'] as $attribute) {
                if (!$this->isValidAttribute($attribute) ||
                    !isset($attributeMap[$attribute['id']]) || empty($attribute['items'])) {
                    continue;
                }

                $attributeId = $attributeMap[$attribute['id']];

                foreach ($attribute['items'] as $attributeItem) {
                    if (!$this->isValidAttributeItem($attributeItem)) {
                        continue;
                    }

                    $itemExternalId = $attributeItem['id'];

                    if (isset($seenAttributeItems[$attributeId][$itemExternalId])) {
                        continue;
                    }

                    $attributeItemInsertStmt->execute([
                        ':attribute_id' => $attributeId,
                        ':external_id' => $itemExternalId,
                        ':display_value' => $attributeItem['displayValue'],
                        ':item_value' => $attributeItem['value'],
                    ]);

                    $seenAttributeItems[$attributeId][$itemExternalId] = true;
                }
            }
        }
    }
}