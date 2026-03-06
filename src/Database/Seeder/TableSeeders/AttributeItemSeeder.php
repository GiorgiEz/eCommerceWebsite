<?php

declare(strict_types=1);

namespace App\Database\Seeder\TableSeeders;

use App\Database\Seeder\AbstractSeeder;
use PDO;

/**
 * Class AttributeItemSeeder
 *
 * Seeds the ATTRIBUTE_ITEMS table using attribute_item data from the dataset.
 */
class AttributeItemSeeder extends AbstractSeeder
{
    /**
     * Inserts attribute_item records into the ATTRIBUTE_ITEMS table.
     *
     * @param PDO   $pdo  Active database connection
     * @param array $data Seed data loaded from JSON
     *
     * @return void
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