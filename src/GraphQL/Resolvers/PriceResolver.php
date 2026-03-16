<?php

namespace App\GraphQL\Resolvers;

/**
 * PriceResolver
 *
 * Resolves product prices for GraphQL queries. Uses the price loader from the request context.
 */
class PriceResolver
{
    /**
     * Returns attributes for a specific product.
     *
     * @param array $product Parent product object from GraphQL resolver chain
     * @param array $args GraphQL field arguments
     * @param array $context Shared request context containing loaders
     *
     * @return array List of prices for the given product
     */
    public static function resolve(array $product, array $args, array $context): array
    {
        return $context['priceLoader']->load($product['id']);
    }
}