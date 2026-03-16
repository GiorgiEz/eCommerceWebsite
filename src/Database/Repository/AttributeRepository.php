<?php

namespace App\Database\Repository;

use App\Database\Config\Database;
use PDO;

/**
 * AttributeRepository
 *
 * Responsible for retrieving product attributes from the database.
 * Provides batch loading of attributes for multiple products in a single query.
 */
class AttributeRepository
{
    /**
     * Retrieves attributes for multiple products.
     *
     * Executes a batch query using the provided product IDs and returns a map indexed by product ID.
     * Each product contains its attributes, and each attribute contains its attribute items.
     *
     * Returned structure:
     * [
     *   product_id => [
     *       [
     *           'external_id' => ...,
     *           'name' => ...,
     *           'type' => ...,
     *           'items' => [
     *               ['external_id' => ..., 'displayValue' => ..., 'value' => ...]
     *           ]
     *       ]
     *   ]
     * ]
     *
     * @param array $productIds List of product database IDs
     * @return array Map of product IDs to their attribute sets
     */
    public function getAttributesByProductIds(array $productIds): array
    {
        $pdo = Database::connect();
        $placeholders = implode(',', array_fill(0, count($productIds), '?'));

        $sql = "
            SELECT
                pa.product_attribute_item_product_id AS product_id,

                a.attribute_id AS attribute_id,
                a.attribute_external_id AS attribute_external_id,
                a.attribute_name AS name,
                a.attribute_type AS type,

                ai.attribute_item_id AS item_id,
                ai.attribute_item_external_id AS item_external_id,
                ai.attribute_item_display_value AS display_value,
                ai.attribute_item_value AS value
            FROM product_attribute_items pa
            JOIN attribute_items ai ON pa.product_attribute_item_attribute_item_id = ai.attribute_item_id
            JOIN attributes a ON ai.attribute_item_attribute_id = a.attribute_id
            WHERE pa.product_attribute_item_product_id IN ($placeholders)
            ORDER BY pa.product_attribute_item_product_id, a.attribute_id
        ";

        $stmt = $pdo->prepare($sql);
        $stmt->execute($productIds);

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $result = [];

        foreach ($rows as $row) {
            $productId = $row['product_id'];
            $attributeId = $row['attribute_id'];

            if (!isset($result[$productId])) {
                $result[$productId] = [];
            }

            if (!isset($result[$productId][$attributeId])) {
                $result[$productId][$attributeId] = [
                    'external_id' => $row['attribute_external_id'],
                    'name' => $row['name'],
                    'type' => $row['type'],
                    'items' => []
                ];
            }

            $result[$productId][$attributeId]['items'][] = [
                'external_id' => $row['item_external_id'],
                'displayValue' => $row['display_value'],
                'value' => $row['value']
            ];
        }

        // convert attribute maps to arrays
        foreach ($result as $productId => $attributes) {
            $result[$productId] = array_values($attributes);
        }

        return $result;
    }
}