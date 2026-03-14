<?php

namespace App\GraphQL\Types\ModelTypes;

use App\GraphQL\Types\BaseType;
use GraphQL\Type\Definition\Type;

/**
 * AttributeSetType
 *
 * GraphQL representation of a AttributeSet entity.
 */
class AttributeSetType extends BaseType
{
    /**
     * Initializes the GraphQL Attribute type schema.
     *
     * Each field represents a property that can be requested by the client.
     */
    public function __construct()
    {
        parent::__construct([
            'name' => 'AttributeSet',
            'fields' => [
                'external_id' => Type::string(),
                'name' => Type::string(),
                'type' => Type::string(),
                'items' => Type::listOf(AttributeItemType::get())
            ]
        ]);
    }
}
