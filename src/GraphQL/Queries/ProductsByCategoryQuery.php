<?php

namespace App\GraphQL\Queries;

use App\Database\Repository\ProductRepository;
use App\GraphQL\Types\ModelTypes\ProductType;
use GraphQL\Type\Definition\Type;

/**
 * GraphQL query for fetching products filtered by category.
 *
 * Allows clients to request a list of products and optionally
 * filter them by category (e.g. "tech", "clothes", or "all").
 */
class ProductsByCategoryQuery
{
    /**
     * Returns the query configuration for the schema.
     */
    public static function build(): array
    {
        return [
            'type' => Type::listOf(new ProductType()),
            'args' => [
                'category' => [
                    'type' => Type::string()
                ]
            ],
            'resolve' => function ($root, $args, $context) {
                $category = $args['category'] ?? 'all';
                $repo = new ProductRepository();
                $products =  $repo->getByCategory($category);

                # Preload prices once
                $productIds = array_column($products, 'id');
                $context['priceLoader']->preload($productIds);

                return $products;
            }
        ];
    }
}