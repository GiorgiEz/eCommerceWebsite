<?php

namespace App\GraphQL\Loaders;

use App\Database\Repository\AttributeRepository;

/**
 * AttributeLoader
 *
 * Concrete loader responsible for batch loading product attributes.
 * Extends AbstractLoader to reuse the shared caching and load() logic.
 *
 * This loader performs a single batch query to fetch attributes for
 * multiple products and stores the results in the inherited cache.
 */
class AttributeLoader extends AbstractLoader
{
    /**
     * Preloads attribute data for multiple products.
     *
     * Executes a batch query through the AttributeRepository and fills the internal cache inherited from AbstractLoader.
     *
     * Expected cache structure:
     *
     * [
     *   product_id => [ ...attribute sets... ],
     *   product_id => [ ...attribute sets... ]
     * ]
     *
     * After this method runs, GraphQL resolvers can retrieve attributes
     * using the inherited load() method without triggering additional database queries.
     *
     * @param array $productIds List of product database IDs
     */
    public function preload(array $productIds): void
    {
        $repo = new AttributeRepository();
        $this->cache = $repo->getAttributesByProductIds($productIds);
    }
}