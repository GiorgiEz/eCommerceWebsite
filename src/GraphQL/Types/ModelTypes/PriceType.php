<?php

namespace App\GraphQL\Types\ModelTypes;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

/**
 * PriceType
 *
 * GraphQL representation of a Price entity.
 */
class PriceType extends ObjectType
{
    /**
     * Initializes the GraphQL Price type schema.
     *
     * Each field represents a property that can be requested by the client.
     */
    public function __construct()
    {
        parent::__construct([
            'name' => 'Price',
            'fields' => [
                'amount' => Type::float(),
                'currency' => new CurrencyType()
            ]
        ]);
    }
}
