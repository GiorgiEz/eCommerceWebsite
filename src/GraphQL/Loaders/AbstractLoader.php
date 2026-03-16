<?php

namespace App\GraphQL\Loaders;

/**
 * AbstractLoader
 *
 * Base class for all GraphQL batch loaders.
 *
 * The purpose of this class is to provide a shared caching mechanism
 * for loaders that fetch related data for multiple products.
 *
 * Concrete loaders (e.g. PriceLoader, AttributeLoader) extend this class
 * and implement the preload() method to populate the cache with data retrieved from repositories.
 */
abstract class AbstractLoader
{
    /**
     * Cache of loaded data indexed by product ID.
     *
     * This allows resolvers to retrieve related data without executing additional database queries.
     */
    protected array $cache = [];

    /**
     * Preloads data for a set of product IDs.
     *
     * This method must be implemented by concrete loaders.
     * It should execute a batch query and populate $this->cache with results indexed by product ID.
     *
     * @param array $productIds List of product database IDs
     */
    abstract public function preload(array $productIds): void;

    /**
     * Retrieves cached data for a single product.
     *
     * This method is used by GraphQL field resolvers to obtain
     * related data (prices, attributes, etc.) without triggering additional database queries.
     *
     * If no data exists for the given product ID, an empty array is returned.
     *
     * @param int $productId Product database ID
     * @return array Cached data related to the product
     */
    public function load(int $productId): array
    {
        return $this->cache[$productId] ?? [];
    }
}