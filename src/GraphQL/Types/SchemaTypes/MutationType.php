<?php

namespace App\GraphQL\Types\SchemaTypes;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use App\GraphQL\Types\OrderTypes\CreateOrderInputType;
use App\GraphQL\Resolvers\CreateOrderResolver;

/**
 * Root GraphQL Mutation type.
 * Defines all write operations available in the schema.
 */
class MutationType extends ObjectType
{
    /**
     * Registers mutation fields and their resolvers.
     */
    public function __construct()
    {
        parent::__construct([
            'name' => 'Mutation',
            'fields' => [
                'createOrder' => [
                    'type' => Type::int(),
                    'args' => [
                        'input' => Type::nonNull(new CreateOrderInputType())
                    ],
                    'resolve' => [CreateOrderResolver::class, 'resolve']
                ]
            ]
        ]);
    }
}