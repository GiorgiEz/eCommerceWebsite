<?php

namespace App\GraphQL\Types\ModelTypes;

use GraphQL\Type\Definition\Type;

/**
 * CurrencyModelType
 *
 * GraphQL representation of a Currency entity.
 */
class CurrencyModelType extends BaseModelType
{
    /**
     * Initializes the GraphQL Currency type schema.
     *
     * Each field represents a property that can be requested by the client.
     */
    public function __construct()
    {
        parent::__construct([
            'name' => 'Currency',
            'fields' => [
                'label' => Type::string(),
                'symbol' => Type::string(),
            ]
        ]);
    }
}