<?php

namespace App\GraphQL\Types\ModelTypes;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

/**
 * AttributeItemType
 *
 * GraphQL representation of a AttributeItem entity.
 */
class AttributeItemType extends ObjectType
{
    /**
     * Initializes the GraphQL AttributeItem type schema.
     *
     * Each field represents a property that can be requested by the client.
     */
    public function __construct()
    {
        parent::__construct([
            'name' => 'Attribute',
            'fields' => [
                'external_id' => Type::string(),
                'displayValue' => Type::string(),
                'value' => Type::string(),
            ]
        ]);
    }
}
