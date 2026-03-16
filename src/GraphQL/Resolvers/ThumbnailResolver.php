<?php

namespace App\GraphQL\Resolvers;

/**
 * ThumbnailResolver
 *
 * Resolves the thumbnail image for a product. Returns the first image from the product gallery.
 */
class ThumbnailResolver
{
    /**
     * Extracts the thumbnail URL from the product gallery.
     *
     * @param array $product Product data containing the gallery field
     *
     * @return string|null First gallery image or null if none exists
     */
    public static function resolve(array $product): ?string
    {
        return $product['gallery'][0] ?? null;
    }
}