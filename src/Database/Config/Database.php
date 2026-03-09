<?php

declare(strict_types=1);

namespace App\Database\Config;

use PDO;
use PDOException;
use RuntimeException;

/**
 * Class Database
 *
 * Responsible for establishing and returning a PDO connection
 * to the MySQL database using credentials from a .env file.
 *
 * This class is framework-agnostic and intended to be used
 * across repositories, services, and GraphQL resolvers.
 */
class Database
{
    /**
     * Creates and returns a PDO connection instance.
     *
     * @return PDO Active PDO database connection
     *
     * @throws RuntimeException When the database connection cannot be established
     */
    public static function connect(): PDO
    {
        // Build MySQL DSN(Data Source Name) string
        $dsn = sprintf(
            'mysql:host=%s;port=%s;dbname=%s;charset=%s',
            $_ENV['DB_HOST'],
            $_ENV['DB_PORT'],
            $_ENV['DB_NAME'],
            $_ENV['DB_CHARSET']
        );

        try {
            // Create PDO connection with strict error handling
            return new PDO(
                $dsn,
                $_ENV['DB_USER'],
                $_ENV['DB_PASS'],
                [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                ]
            );
        } catch (PDOException $exception) {
            // Wrap low-level PDO exception in a runtime exception
            throw new RuntimeException(
                'Database connection failed: ' . $exception->getMessage()
            );
        }
    }
}