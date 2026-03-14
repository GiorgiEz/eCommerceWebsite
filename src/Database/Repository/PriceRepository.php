<?php

namespace App\Database\Repository;

use App\Database\Config\Database;
use PDO;

/**
 * PriceRepository
 *
 * Responsible for retrieving price data from the database.
 * Provides batch loading of prices for multiple products in a single query.
 */
class PriceRepository
{
    /**
     * Retrieves prices for multiple products using their database IDs.
     *
     * The method builds a dynamic SQL query with placeholders for each product ID and executes a single batch query.
     * The result is returned as a map indexed by product ID:
     * [
     *   product_id => [
     *       ['amount' => ..., 'currency' => [...]],
     *       ...
     *   ], ...
     * ]
     *
     * This structure allows loaders/resolvers to quickly access prices for a specific product without additional queries.
     *
     * @param array $productIds List of product database IDs
     * @return array Map of product IDs to their price lists
     */
    public function getPricesByProductIds(array $productIds): array
    {
        $pdo = Database::connect();

        // Create a placeholder for each product ID (?,?,?,...)
        $placeholders = implode(',', array_fill(0, count($productIds), '?'));

        // Batch query retrieving prices and their associated currency
        $sql = "
                SELECT 
                    p.product_id as id,
                    pr.price_amount AS amount,
                    c.currency_label AS label,
                    c.currency_symbol AS symbol
                FROM prices pr
                JOIN currencies c ON pr.price_currency_id = c.currency_id
                JOIN products p ON pr.price_product_id = p.product_id
                WHERE p.product_id IN ($placeholders)
            ";

        $stmt = $pdo->prepare($sql);

        // Bind product IDs to placeholders
        $stmt->execute($productIds);

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $priceMap = [];

        // Group prices by product ID
        foreach ($rows as $row) {
            $productId = $row['id'];

            $priceMap[$productId][] = [
                'amount' => (float) $row['amount'],
                'currency' => [
                    'label' => $row['label'],
                    'symbol' => $row['symbol']
                ]
            ];
        }

        return $priceMap;
    }
}