<?php

namespace App\GraphQL\Types\ModelTypes;

use App\GraphQL\Types\BaseType;
use GraphQL\Type\Definition\Type;

/**
 * CategoryType
 *
 * GraphQL representation of a Category entity.
 */
class CategoryType extends BaseType
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
