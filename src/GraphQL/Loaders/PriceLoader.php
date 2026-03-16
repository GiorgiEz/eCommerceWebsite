<?php

namespace App\GraphQL\Loaders;

use App\Database\Repository\PriceRepository;

/**
 * PriceLoader
 *
 * Concrete loader responsible for batch loading product prices.
 * Extends AbstractLoader to reuse the shared caching and load() logic.
 *
 * This loader performs a single batch query to fetch prices for multiple
 * products and stores the results in the inherited cache array.
 */
class PriceLoader extends AbstractLoader
{
    /**
     * Preloads price data for multiple products.
     *
     * Executes a batch query through the PriceRepository and fills the internal cache inherited from AbstractLoader.
     *
     * The cache structure should be:
     *
     * [
     *   product_id => [ ...price objects... ],
     *   product_id => [ ...price objects... ]
     * ]
     *
     * After this method runs, resolvers can retrieve prices using load().
     *
     * @param array $productIds List of product database IDs
     */
    public function preload(array $productIds): void
    {
        $repo = new PriceRepository();
        $this->cache = $repo->getPricesByProductIds($productIds);
    }
}