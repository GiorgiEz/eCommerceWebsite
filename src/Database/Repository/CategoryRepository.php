<?php

namespace App\Database\Repository;

use App\Database\Config\Database;
use PDO;

/**
 * CategoryRepository
 *
 * Responsible for retrieving category data from the database.
 * Provides methods used by GraphQL queries to fetch available product categories.
 */
class CategoryRepository
{
    /**
     * Retrieves all product categories.
     *
     * Returns category names formatted for the GraphQL schema.
     *
     * Example returned structure:
     * [['name' => 'all'], ['name' => 'clothes'], ['name' => 'tech']]
     *
     * @return array List of categories
     */
    public function getAll(): array
    {
        $pdo = Database::connect();

        // Execute query retrieving all category names
        $stmt = $pdo->query("SELECT category_name as name FROM categories");

        // Return results as associative arrays
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}