<?php

declare(strict_types=1);

namespace App\Database\Seeder;

use PDO;

/**
 * Category Seeder class. Inserts data in CATEGORIES table
 */
class CategorySeeder extends AbstractSeeder
{
    /**
     * Extracts categories specific data, creates sql statement and inserts the data
     *
     * @param  PDO $pdo Database connection object
     * @param  array $data JSON data, given as array
     *
     * @return void Executes the table insertion logic for CATEGORIES table
     */
    protected function run(PDO $pdo, array $data): void
    {
        $categories = $data['data']['categories'] ?? [];

        $stmt = $pdo->prepare(
            'INSERT IGNORE INTO CATEGORIES (CATEGORY_NAME) VALUES (:name)'
        );

        foreach ($categories as $category) {
            $name = $category['name'] ?? null;

            if ($name === null || trim($name) === '') {
                continue;
            }
            $stmt->execute([':name' => $name]);
        }
    }
}