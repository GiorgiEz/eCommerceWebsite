<?php

declare(strict_types=1);

namespace App\Database\Seeder;

use PDO;

/**
 * Interface SeederInterface
 *
 * Defines the contract for all database seeders.
 * Each seeder is responsible for inserting data into a specific table
 * or related set of tables using the provided dataset.
 */
interface SeederInterface
{
    /**
     * Executes the database seeding logic.
     *
     * @param PDO   $pdo  Active database connection
     * @param array $data Seed data loaded from the JSON dataset
     *
     * @return void
     */
    public function seed(PDO $pdo, array $data): void;
}