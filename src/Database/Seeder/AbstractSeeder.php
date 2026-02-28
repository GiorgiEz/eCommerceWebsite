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

    protected function isValidProduct(array $product): bool
    {
        return isset(
            $product['id'],
            $product['name'],
            $product['inStock'],
            $product['description'],
            $product['brand'],
            $product['category']
        );
    }

    protected function isValidPrice(array $price): bool
    {
        return isset(
            $price['amount'],
            $price['currency'],
            $price['currency']['symbol'],
            $price['currency']['label']
        );
    }

    protected function isValidAttribute(array $attribute): bool
    {
        return isset(
            $attribute['id'],
            $attribute['name'],
            $attribute['type']
        );
    }

    protected function isValidAttributeItem(array $attributeItem): bool
    {
        return isset(
            $attributeItem['displayValue'],
            $attributeItem['value'],
            $attributeItem['id']
        );
    }
}