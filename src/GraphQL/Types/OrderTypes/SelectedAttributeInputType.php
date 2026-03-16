<?php

namespace App\GraphQL\Types\OrderTypes;

use GraphQL\Type\Definition\Type;

/**
 * GraphQL input type representing a selected attribute for a product.
 * Identifies the attribute and the chosen attribute item.
 */
class SelectedAttributeInputType extends BaseOrderType
{
    /**
     * Defines fields describing a selected attribute option.
     */
    public function __construct()
    {
        parent::__construct([
            'name' => 'SelectedAttributeInput',
            'fields' => [
                'attributeId' => Type::nonNull(Type::string()),
                'attributeItemId' => Type::nonNull(Type::string())
            ]
        ]);
    }
}