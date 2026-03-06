<?php

declare(strict_types=1);

namespace App\Database\Seeder\TableSeeders;

use App\Database\Seeder\AbstractSeeder;
use PDO;

/**
 * Class CategorySeeder
 *
 * Seeds the CATEGORIES table using category data from the dataset.
 */
class CategorySeeder extends AbstractSeeder
{
    /**
     * Inserts category records into the CATEGORIES table.
     *
     * @param PDO   $pdo  Active database connection
     * @param array $data Seed data loaded from JSON
     *
     * @return void
     */
    protected function run(PDO $pdo, array $data): void
    {
        $categories = $data['data']['categories'] ?? [];

        $categoryInsertStmt = $pdo->prepare(
            'INSERT IGNORE INTO CATEGORIES (CATEGORY_NAME) VALUES (:name)'
        );

        foreach ($categories as $category) {
            if (!isset($category['name'])) {
                continue;
            }

            $categoryInsertStmt->execute([
                ':name' => $category['name']
            ]);
        }
    }
}