<?php

namespace App\GraphQL\Resolvers;

/**
 * AttributeResolver
 *
 * Resolves product attributes for GraphQL queries. Uses the attribute loader from the request context.
 */
class AttributeResolver
{
    /**
     * Returns attributes for a specific product.
     *
     * @param array $product Parent product object from GraphQL resolver chain
     * @param array $args GraphQL field arguments
     * @param array $context Shared request context containing loaders
     *
     * @return array List of attributes for the given product
     */
    public static function resolve(array $product, array $args, array $context): array
    {
        return $context['attributeLoader']->load($product['id']);
    }
}