<?php

declare(strict_types=1);

namespace App\Database\Seeder;

use PDO;

class SeederManager
{
    /** @var SeederInterface[] */
    private array $seeders;

    public function __construct(array $seeders)
    {
        $this->seeders = $seeders;
    }

    public function run(PDO $pdo, array $data): void
    {
        foreach ($this->seeders as $seeder) {
            $seeder->seed($pdo, $data);
        }
    }
}