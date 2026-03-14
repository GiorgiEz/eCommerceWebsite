<?php

namespace App\GraphQL\Types\ModelTypes;

use GraphQL\Type\Definition\Type;

/**
 * CategoryModelType
 *
 * GraphQL representation of a Category entity.
 */
class CategoryModelType extends BaseModelType
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
