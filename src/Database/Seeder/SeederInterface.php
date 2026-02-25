<?php

declare(strict_types=1);

namespace App\Database\Seeder;

use PDO;

/**
 * Interface to define the general database seeder information.
 */
interface SeederInterface
{
    /**
     * Main method to insert data in database for each Seeder class
     *
     * @param  PDO $pdo Database connection object
     * @param  array $data JSON data, given as array
     *
     * @return void Executes the general seeder logic, resulting in insertion of data
     */
    public function seed(PDO $pdo, array $data): void;
}