<?php

declare(strict_types=1);

namespace App\Database\Seeder;

use PDO;

interface SeederInterface
{
    public function seed(PDO $pdo, array $data): void;
}