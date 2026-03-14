<?php

namespace App\GraphQL\Types\ModelTypes;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

use App\GraphQL\Resolvers\ThumbnailResolver;
use App\GraphQL\Resolvers\AttributeResolver;
use App\GraphQL\Resolvers\PriceResolver;

/**
 * ProductType
 *
 * GraphQL representation of a Product entity.
 * Defines the fields that can be queried for a product and
 * delegates complex field resolution to dedicated resolver classes.
 */
class ProductType extends ObjectType
{
    /**
     * Initializes the GraphQL Product type schema.
     *
     * Each field represents a property that can be requested by the client.
     * Some fields are resolved directly from the product data, while others
     * (thumbnail, attributes, prices) are resolved through separate resolver
     * classes to keep business logic outside of the schema definition.
     */
    public function __construct()
    {
        parent::__construct([
            'name' => 'Product',

            'fields' => function () {
                return [
                    'external_id' => Type::string(),
                    'name' => Type::string(),
                    'brand' => Type::string(),
                    'inStock' => Type::boolean(),
                    'gallery' => Type::listOf(Type::string()),
                    'thumbnail' => [
                        'type' => Type::string(),
                        'resolve' => [ThumbnailResolver::class, 'resolve']
                    ],
                    'description' => Type::string(),
                    'category' => Type::string(),
                    'attributes' => [
                        'type' => Type::listOf(new AttributeSetType()),
                        'resolve' => [AttributeResolver::class, "resolve"]
                    ],
                    'prices' => [
                        'type' => Type::listOf(new PriceType()),
                        'resolve' => [PriceResolver::class, 'resolve']
                    ],
                ];
            }
        ]);
    }
}