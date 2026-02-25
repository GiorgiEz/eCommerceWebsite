<?php

declare(strict_types=1);

namespace App\Database\Seeder;

use PDO;
use Throwable;

/**
 * Seeder abstract class with implemented 'seed' method and 'run' abstract method
 */
abstract class AbstractSeeder implements SeederInterface
{
    /**
     * Opens connection to database, runs seeder function and commits
     *
     * @param  PDO $pdo Database connection object
     * @param  array $data JSON data, given as array
     *
     * @return void Executes the general seeder logic, resulting in insertion of data
     */
    final public function seed(PDO $pdo, array $data): void
    {
        try {
            $pdo->beginTransaction();

            $this->run($pdo, $data);

            $pdo->commit();
        } catch (Throwable $e) {
            $pdo->rollBack();
            throw $e;
        }
    }

    /**
     * 'run' has a specific implementation for each seeder class, it should execute SQL query, inserting data into table
     *
     * @param  PDO $pdo Database connection object
     * @param  array $data JSON data, given as array
     *
     * @return void Executes the general seeder logic, resulting in insertion of data
     */
    abstract protected function run(PDO $pdo, array $data): void;
}