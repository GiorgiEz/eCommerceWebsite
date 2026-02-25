<?php

declare(strict_types=1);

namespace App\Database\Seeder;

use PDO;

/**
 * Seeder Manager class that collects all seeder classes and runs seed method for each class
 */
class SeederManager
{
    /** @var SeederInterface[] */
    private array $seeders;

    public function __construct(array $seeders)
    {
        $this->seeders = $seeders;
    }

    /**
     * Iterates over the seeders array and runs seed function for each seeder
     *
     * @param  PDO $pdo Database connection object
     * @param  array $data JSON data, given as array
     *
     * @return void
     */
    public function run(PDO $pdo, array $data): void
    {
        foreach ($this->seeders as $seeder) {
            $seeder->seed($pdo, $data);
        }
    }
}