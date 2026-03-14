<?php

namespace App\Database\Repository;

use App\Database\Config\Database;
use PDO;

/**
 * Repository responsible for persisting orders and related entities
 * (order items and selected attribute items) into the database.
 */
class OrderRepository
{
    /**
     * Creates a new order and all associated order items and attribute selections.
     *
     * @param string $orderDate        Date of the order provided by the frontend
     * @param float  $totalAmount      Total order amount calculated on the frontend
     * @param array  $items            List of order items with selected attributes
     *
     * @return int ID of the created order
     *
     * @throws \Throwable Rolls back the transaction if any query fails
     */
    public function createOrder(string $orderDate, float $totalAmount, array $items): int
    {
        $pdo = Database::connect();

        // Ensure all inserts succeed or fail together (Atomicity)
        $pdo->beginTransaction();

        try {
            // Insert main order record
            $stmt = $pdo->prepare("
                INSERT INTO ORDERS (ORDER_DATE, ORDER_TOTAL_AMOUNT) VALUES (?, ?)
            ");

            $stmt->execute([$orderDate, $totalAmount]);
            $orderId = (int)$pdo->lastInsertId();

            // Insert each product added to the order
            foreach ($items as $item) {
                $productId = $this->getProductInternalId($pdo, $item['productId']);

                $stmt = $pdo->prepare("
                    INSERT INTO ORDER_ITEMS
                    (
                        ORDER_ITEM_ORDER_ID,
                        ORDER_ITEM_PRODUCT_ID,
                        ORDER_ITEM_QUANTITY,
                        ORDER_ITEM_PRICE
                    )
                    VALUES (?, ?, ?, ?)
                ");

                $stmt->execute([$orderId, $productId, $item['quantity'], $item['price']]);
                $orderItemId = (int)$pdo->lastInsertId();

                // Insert selected attribute items for this order item
                foreach ($item['attributes'] as $attribute) {
                    $attributeItemId = $this->getAttributeItemInternalId(
                        $pdo,
                        $attribute['attributeId'],
                        $attribute['attributeItemId']
                    );

                    $stmt = $pdo->prepare("
                        INSERT INTO ORDER_ITEM_ATTRIBUTES
                        (
                            ORDER_ITEM_ATTRIBUTE_ORDER_ITEM_ID,
                            ORDER_ITEM_ATTRIBUTE_ATTRIBUTE_ITEM_ID
                        )
                        VALUES (?, ?)
                    ");

                    $stmt->execute([$orderItemId, $attributeItemId]);
                }
            }

            // Finalize transaction
            $pdo->commit();

            return $orderId;

        } catch (\Throwable $e) {
            // Rollback if any part of the order creation fails
            $pdo->rollBack();
            throw $e;
        }
    }

    /**
     * Resolves a product's internal database ID from its external identifier.
     */
    private function getProductInternalId(PDO $pdo, string $externalId): int
    {
        $stmt = $pdo->prepare("
            SELECT PRODUCT_ID
            FROM PRODUCTS
            WHERE PRODUCT_EXTERNAL_ID = ?
        ");

        $stmt->execute([$externalId]);

        return (int)$stmt->fetchColumn();
    }

    /**
     * Resolves the internal attribute item ID using attribute and item identifiers.
     * Both values are required because attribute item external IDs may repeat
     * across different attributes (e.g., "Yes", "No").
     */
    private function getAttributeItemInternalId(PDO $pdo, string $attributeId, string $attributeItemId): int
    {
        $stmt = $pdo->prepare("
            SELECT ai.ATTRIBUTE_ITEM_ID
            FROM ATTRIBUTE_ITEMS ai
            JOIN ATTRIBUTES a
            ON ai.ATTRIBUTE_ITEM_ATTRIBUTE_ID = a.ATTRIBUTE_ID
            WHERE a.ATTRIBUTE_EXTERNAL_ID = ?
            AND ai.ATTRIBUTE_ITEM_EXTERNAL_ID = ?
        ");

        $stmt->execute([
            $attributeId,
            $attributeItemId
        ]);

        return (int)$stmt->fetchColumn();
    }
}