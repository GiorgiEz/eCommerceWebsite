<?php

namespace App\GraphQL\Types\ModelTypes;

use App\GraphQL\Types\BaseType;
use GraphQL\Type\Definition\Type;

/**
 * CurrencyType
 *
 * GraphQL representation of a Currency entity.
 */
class CurrencyType extends BaseType
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