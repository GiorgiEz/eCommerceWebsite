<?php

namespace App\GraphQL\Types\OrderTypes;

use GraphQL\Type\Definition\Type;

/**
 * GraphQL input type representing the payload required to create an order.
 * Contains order metadata and the list of items included in the order.
 */
class CreateOrderInputType extends BaseOrderType
{
    /**
     * Defines fields accepted by the createOrder mutation.
     */
    public function __construct()
    {
        parent::__construct([
            'name' => 'CreateOrderInput',
            'fields' => [
                'orderDate' => Type::nonNull(Type::string()),
                'orderTotalAmount' => Type::nonNull(Type::float()),

                'items' => Type::nonNull(
                    Type::listOf(Type::nonNull(OrderItemInputType::get()))
                )
            ]
        ]);
    }
}