<?php

namespace App\GraphQL\Types\SchemaTypes;

use App\GraphQL\Queries\ProductsByCategoryQuery;
use App\GraphQL\Queries\ProductByIdQuery;
use App\GraphQL\Queries\CategoryQuery;
use GraphQL\Type\Definition\ObjectType;

/**
 * Root GraphQL Query type.
 *
 * Defines all top-level queries that clients can execute
 * (e.g. products, product by id, categories).
 */
class QueryType extends ObjectType
{
    /**
     * Initializes the Query type and registers all available
     * GraphQL query fields with their resolvers.
     */
    public function __construct()
    {
        parent::__construct([
            'name' => 'Query',
            'fields' => [
                'products' => ProductsByCategoryQuery::build(),  // fetch list of products by category
                'product' => ProductByIdQuery::build(), // fetch a single product by id
                'categories' => CategoryQuery::build(), // fetch all categories
            ]
        ]);
    }
};