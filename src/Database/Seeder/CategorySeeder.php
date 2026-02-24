<?php

declare(strict_types=1);

namespace App\Database\Seeder;

use PDO;

class CategorySeeder extends AbstractSeeder
{
    protected function run(PDO $pdo, array $data): void
    {
        $categories = $data['data']['categories'] ?? [];

        $stmt = $pdo->prepare(
            'INSERT IGNORE INTO CATEGORIES (CATEGORY_NAME) VALUES (:name)'
        );

        foreach ($categories as $category) {
            $name = $category['name'] ?? null;

            if (!empty($name) && strlen($name) <= 20) {
                $stmt->execute([':name' => $name]);
            }
        }
    }
}