<?php

namespace App\GraphQL\Types\OrderTypes;

use GraphQL\Type\Definition\InputObjectType;
use GraphQL\Type\Definition\Type;

/**
 * GraphQL input type representing a single item in an order.
 * Includes product identifier, quantity, price at purchase time,
 * and the selected attribute items for that product.
 */
class OrderItemInputType extends InputObjectType
{
    /**
     * Defines fields required for each order item in the createOrder mutation.
     */
    public function __construct()
    {
        parent::__construct([
            'name' => 'OrderItemInput',
            'fields' => [
                'productId' => Type::nonNull(Type::string()),
                'quantity' => Type::nonNull(Type::int()),
                'price' => Type::nonNull(Type::float()),
                'attributes' => Type::nonNull(
                    Type::listOf(Type::nonNull(new SelectedAttributeInputType()))
                )
            ]
        ]);
    }
}