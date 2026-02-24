<?php

declare(strict_types=1);

namespace App\Database\Config;

use PDO;
use PDOException;
use RuntimeException;

/**
 * Configuration class to make a connection to the Database
 */
class Database
{
    /**
     * Connects to Database using given credentials
     *
     * @param string $envPath Absolute or relative path to the .env file containing Database credentials.
     *
     * @return PDO object after successful connection.
     *@throws RuntimeException If the file does not exist, cannot be read.
     *
     */
    public static function connect(string $envPath): PDO
    {
        if (!file_exists($envPath)) {
            throw new RuntimeException('.env file not found.');
        }

        $env = parse_ini_file($envPath);

        if ($env === false) {
            throw new RuntimeException('Invalid .env file.');
        }

        $dsn = sprintf(
            'mysql:host=%s;port=%s;dbname=%s;charset=%s',
            $env['DB_HOST'],
            $env['DB_PORT'],
            $env['DB_NAME'],
            $env['DB_CHARSET']
        );

        try {
            return new PDO($dsn, $env['DB_USER'], $env['DB_PASS'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
        } catch (PDOException $e) {
            throw new RuntimeException($e->getMessage());
        }
    }
}