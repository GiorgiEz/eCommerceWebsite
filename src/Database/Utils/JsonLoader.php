<?php

declare(strict_types=1);

namespace App\Database\Utils;

use RuntimeException;

/**
 * Class JsonLoader
 *
 * Utility class responsible for loading and decoding JSON files.
 * Used for database seeders and static data loading.
 */
class JsonLoader
{
    /**
     * Loads and decodes a JSON file into an associative array.
     *
     * @param string $path Path to the JSON file (absolute or relative)
     *
     * @return array Decoded JSON data as an associative array
     *
     * @throws RuntimeException When the file does not exist, cannot be read,
     *                          or contains invalid JSON
     */
    public static function load(string $path): array
    {
        // Ensure the JSON file exists
        if (!file_exists($path)) {
            throw new RuntimeException('JSON file not found: ' . $path);
        }

        // Read file contents
        $content = file_get_contents($path);

        if ($content === false) {
            throw new RuntimeException('Unable to read JSON file.');
        }

        // Decode JSON into an associative array
        $data = json_decode($content, true);

        // Validate JSON decoding
        if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
            throw new RuntimeException(
                'Invalid JSON: ' . json_last_error_msg()
            );
        }

        return $data;
    }
}