<?php

declare(strict_types=1);

namespace App\Database\Seeder;

use PDO;
use Throwable;

/**
 * Class AbstractSeeder
 *
 * Base class for all database seeders.
 * Provides common functionality such as transaction handling while
 * delegating table-specific insertion logic to child classes.
 */
abstract class AbstractSeeder implements SeederInterface
{
    /**
     * Executes the seeding process inside a database transaction.
     *
     * @param PDO   $pdo  Active database connection
     * @param array $data Seed data loaded from JSON
     *
     * @return void
     *
     * @throws Throwable Rethrows any exception that occurs during seeding
     */
    final public function seed(PDO $pdo, array $data): void
    {
        try {
            $pdo->beginTransaction();

            $this->run($pdo, $data);

            $pdo->commit();
        } catch (Throwable $exception) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }

            throw $exception;
        }
    }

    /**
     * Executes table-specific seeding logic.
     *
     * Concrete seeders must implement this method to insert
     * their respective dataset into the database.
     *
     * @param PDO   $pdo  Active database connection
     * @param array $data Seed data loaded from JSON
     *
     * @return void
     */
    abstract protected function run(PDO $pdo, array $data): void;

    /**
     * Validates the required fields of a product dataset.
     */
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

    /**
     * Validates the required fields of a price dataset.
     */
    protected function isValidPrice(array $price): bool
    {
        return isset(
            $price['amount'],
            $price['currency'],
            $price['currency']['symbol'],
            $price['currency']['label']
        );
    }

    /**
     * Validates the required fields of an attribute dataset.
     */
    protected function isValidAttribute(array $attribute): bool
    {
        return isset(
            $attribute['id'],
            $attribute['name'],
            $attribute['type']
        );
    }

    /**
     * Validates the required fields of an attribute item dataset.
     */
    protected function isValidAttributeItem(array $attributeItem): bool
    {
        return isset(
            $attributeItem['displayValue'],
            $attributeItem['value'],
            $attributeItem['id']
        );
    }
}