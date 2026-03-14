<?php

namespace App\GraphQL\Types\ModelTypes;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

/**
 * CategoryType
 *
 * GraphQL representation of a Category entity.
 */
class CategoryType extends ObjectType
{
    /**
     * Initializes the GraphQL Category type schema.
     *
     * Each field represents a property that can be requested by the client.
     */
    public function __construct()
    {
        parent::__construct([
            'name' => 'Category',
            'fields' => [
                'name' => Type::string(),
            ]
        ]);
    }
}
