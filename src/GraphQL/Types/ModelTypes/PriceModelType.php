<?php

namespace App\GraphQL\Types\ModelTypes;

use GraphQL\Type\Definition\Type;

/**
 * PriceModelType
 *
 * GraphQL representation of a Price entity.
 */
class PriceModelType extends BaseModelType
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
                'currency' => CurrencyModelType::get()
            ]
        ]);
    }
}
