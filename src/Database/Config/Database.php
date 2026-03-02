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
     * @param string $envPath Path to the .env configuration file
     *
     * @return PDO Active PDO database connection
     *
     * @throws RuntimeException When the .env file is missing, invalid,
     *                          or a database connection cannot be established
     */
    public static function connect(string $envPath): PDO
    {
        // Ensure configuration file exists
        if (!file_exists($envPath)) {
            throw new RuntimeException('Database configuration file (.env) not found.');
        }

        // Parse environment variables from .env file
        $env = parse_ini_file($envPath);

        if ($env === false) {
            throw new RuntimeException('Unable to read database configuration.');
        }

        // Build MySQL DSN(Data Source Name) string
        $dsn = sprintf(
            'mysql:host=%s;port=%s;dbname=%s;charset=%s',
            $env['DB_HOST'],
            $env['DB_PORT'],
            $env['DB_NAME'],
            $env['DB_CHARSET']
        );

        try {
            // Create PDO connection with strict error handling
            return new PDO(
                $dsn,
                $env['DB_USER'],
                $env['DB_PASS'],
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