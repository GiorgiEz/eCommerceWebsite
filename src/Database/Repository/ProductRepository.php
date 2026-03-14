<?php

namespace App\Database\Repository;

use App\Database\Config\Database;
use PDO;

/**
 * ProductRepository
 *
 * Responsible for retrieving product data from the database.
 * Provides methods for fetching products by category or by external ID
 * and transforming raw SQL rows into structured product arrays.
 */
class ProductRepository
{

    /**
     * Base SQL query used to retrieve product data.
     *
     * Includes joins with categories and images tables so that
     * all necessary product information can be retrieved in a single query.
     *
     * @return string SQL query fragment
     */
    private function baseQuery(): string
    {
        return "
            SELECT 
                p.product_id AS id,
                p.product_external_id AS external_id,
                p.product_name AS name,
                p.product_brand AS brand,
                c.category_name AS category,
                p.product_description AS description,
                p.product_in_stock AS inStock,
                i.image_url
            FROM products p
            JOIN categories c ON c.category_id = p.product_category_id
            LEFT JOIN images i ON p.product_id = i.image_product_id
        ";
    }

    /**
     * Converts raw database rows into structured product arrays.
     *
     * Because products may have multiple images, the SQL query returns multiple rows per product.
     * This method groups those rows by product ID and builds a gallery array containing all image URLs.
     *
     * @param array $rows Raw rows returned from the database
     * @return array Hydrated list of products
     */
    private function hydrateProducts(array $rows): array
    {
        $products = [];

        foreach ($rows as $row) {
            $id = $row['id'];

            if (!isset($products[$id])) {
                $products[$id] = [
                    'id' => $id,
                    'external_id' => $row['external_id'],
                    'name' => $row['name'],
                    'brand' => $row['brand'],
                    'category' => $row['category'],
                    'description' => $row['description'],
                    'inStock' => (bool)$row['inStock'],
                    'gallery' => []
                ];
            }

            if ($row['image_url']) {
                $products[$id]['gallery'][] = $row['image_url'];
            }
        }

        return array_values($products);
    }

    /**
     * Retrieves products filtered by category.
     *
     * If the category is "all", all products are returned. Otherwise, the result is filtered using the category name.
     *
     * @param string $category Product category name
     * @return array List of hydrated products
     */
    public function getByCategory($category): array
    {
        $pdo = Database::connect();

        $sql = $this->baseQuery();

        // Apply filter only if category is not "all"
        if ($category !== 'all') {
            $sql .= " WHERE c.category_name = :category";
        }

        $sql .= " ORDER BY p.product_id";
        $stmt = $pdo->prepare($sql);

        if ($category !== 'all') {
            $stmt->execute(['category' => $category]);
        } else {
            $stmt->execute();
        }

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $this->hydrateProducts($rows);
    }

    /**
     * Retrieves a single product by its external identifier.
     *
     * External IDs are used by the GraphQL API instead of database primary keys
     * to avoid exposing internal database structure.
     *
     * @param string $externalId Public product identifier
     * @return array|null Hydrated product or null if not found
     */
    public function getById(string $externalId): ?array
    {
        $pdo = Database::connect();

        $sql = $this->baseQuery() . " WHERE p.product_external_id = :external_id";

        $stmt = $pdo->prepare($sql);
        $stmt->execute(['external_id' => $externalId]);

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!$rows) {
            return null;
        }

        return $this->hydrateProducts($rows)[0];
    }
}