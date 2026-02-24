<?php

declare(strict_types=1);

namespace App\Database\Seeder;

use PDO;
use Throwable;

abstract class AbstractSeeder implements SeederInterface
{
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

    abstract protected function run(PDO $pdo, array $data): void;
}