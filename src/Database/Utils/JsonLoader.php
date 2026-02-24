<?php

declare(strict_types=1);

namespace App\Database\Utils;

use RuntimeException;

/**
 * Utility class for loading and decoding JSON files.
 */
class JsonLoader
{
    /**
     * Loads and decodes a JSON file from the given path.
     *
     * @param  string $path Absolute or relative path to the JSON file.
     *
     * @throws RuntimeException If the file does not exist, cannot be read,
     *                          or contains invalid JSON.
     *
     * @return array The decoded JSON data as an associative array.
     */
    public static function load(string $path): array
    {
        if (!file_exists($path)) {
            throw new RuntimeException("JSON file not found: $path");
        }

        $content = file_get_contents($path);

        if ($content === false) {
            throw new RuntimeException("Failed to read JSON file.");
        }

        $data = json_decode($content, true);

        if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
            throw new RuntimeException(json_last_error_msg());
        }

        return $data;
    }
}