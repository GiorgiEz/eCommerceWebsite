<?php

namespace App\GraphQL\Types\ModelTypes;

use GraphQL\Type\Definition\Type;

/**
 * AttributeItemModelType
 *
 * GraphQL representation of a AttributeItem entity.
 */
class AttributeItemModelType extends BaseModelType
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
