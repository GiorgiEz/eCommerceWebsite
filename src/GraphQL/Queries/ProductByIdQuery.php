<?php

namespace App\GraphQL\Queries;

use App\Database\Repository\ProductRepository;
use App\GraphQL\Types\ModelTypes\ProductType;
use GraphQL\Type\Definition\Type;

/**
 * GraphQL query for fetching a single product by ID.
 *
 * Allows clients to request a single product by its ID.
 */
class ProductByIdQuery
{
    /**
     * Returns the query configuration for the schema.
     */
    public static function build(): array
    {
        return [
            'type' => new ProductType(),
            'args' => [
                'external_id' => [
                    'type' => Type::nonNull(Type::string())
                ]
            ],
            'resolve' => function ($root, $args, $context) {
                $repo = new ProductRepository();
                $product = $repo->getById($args['external_id']);

                if (!$product) {
                    return null;
                }

                # Preload prices and attributes once
                $productId = [$product['id']];
                $context['priceLoader']->preload($productId);
                $context['attributeLoader']->preload($productId);

                return $product;
            }
        ];
    }
}