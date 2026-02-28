<?php

declare(strict_types=1);

namespace App\Database\Seeder\TableSeeders;

use App\Database\Seeder\AbstractSeeder;
use PDO;

/**
 * ProductAttributeItem Seeder class. Inserts data in PRODUCT_ATTRIBUTE_ITEMS table
 */
class ProductAttributeItemSeeder extends AbstractSeeder
{
    /**
     * Extracts product_attribute_items specific data, creates sql statement and inserts the data
     *
     * @param  PDO $pdo Database connection object
     * @param  array $data JSON data, given as array
     *
     * @return void Executes the table insertion logic for PRODUCT_ATTRIBUTE_ITEMS table
     */
    protected function run(PDO $pdo, array $data): void
    {
        $products = $data['data']['products'] ?? [];

        $productStmt = $pdo->query('SELECT PRODUCT_EXTERNAL_ID, PRODUCT_ID FROM PRODUCTS');
        $productMap = $productStmt->fetchAll(PDO::FETCH_KEY_PAIR);

        $attributeStmt = $pdo->query('SELECT ATTRIBUTE_EXTERNAL_ID, ATTRIBUTE_ID FROM ATTRIBUTES');
        $attributeMap = $attributeStmt->fetchAll(PDO::FETCH_KEY_PAIR);

        $attributeItemStmt = $pdo->query
            ('SELECT
                        ai.ATTRIBUTE_ITEM_ID,
                        ai.ATTRIBUTE_ITEM_EXTERNAL_ID,
                        a.ATTRIBUTE_EXTERNAL_ID
                    FROM ATTRIBUTE_ITEMS ai
                    JOIN ATTRIBUTES a ON ai.ATTRIBUTE_ITEM_ATTRIBUTE_ID = a.ATTRIBUTE_ID');
        $attributeItemMap = [];

        while ($row = $attributeItemStmt->fetch(PDO::FETCH_ASSOC)) {
            $attributeItemMap[$row['ATTRIBUTE_EXTERNAL_ID']][$row['ATTRIBUTE_ITEM_EXTERNAL_ID']]
                = $row['ATTRIBUTE_ITEM_ID'];
        }

        $productAttributeItemStmt = $pdo->prepare(
            'INSERT IGNORE INTO PRODUCT_ATTRIBUTE_ITEMS 
                (PRODUCT_ATTRIBUTE_ITEM_ATTRIBUTE_ITEM_ID, PRODUCT_ATTRIBUTE_ITEM_PRODUCT_ID)
                VALUES (:attribute_item_id, :product_id)'
        );

        foreach ($products as $product) {
            if (!$this->isValidProduct($product) ||
                !isset($productMap[$product['id']]) || empty($product['attributes'])) {
                continue;
            }

            foreach ($product['attributes'] as $attribute) {
                if (!$this->isValidAttribute($attribute) ||
                    !isset($attributeMap[$attribute['id']]) || empty($attribute['items'])) {
                    continue;
                }

                foreach ($attribute['items'] as $attributeItem) {
                    if (!$this->isValidAttributeItem($attributeItem) ||
                        !isset($attributeItemMap[$attribute['id']][$attributeItem['id']])) {
                        continue;
                    }

                    $productAttributeItemStmt->execute([
                        ':attribute_item_id' => $attributeItemMap[$attribute['id']][$attributeItem['id']],
                        ':product_id' => $productMap[$product['id']],
                    ]);
                }
            }
        }
    }
}