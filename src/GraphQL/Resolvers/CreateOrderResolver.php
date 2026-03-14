<?php

namespace App\GraphQL\Resolvers;

use App\Database\Repository\OrderRepository;

/**
 * GraphQL resolver responsible for handling the createOrder mutation.
 * Receives input from the GraphQL layer and delegates order creation
 * to the database repository.
 */
class CreateOrderResolver
{
    /**
     * Executes the createOrder mutation.
     *
     * @param mixed $root Root resolver value (unused for mutations)
     * @param array $args GraphQL arguments containing the order input
     *
     * @return int ID of the created order
     *
     * @throws \Throwable If order creation fails
     */
    public static function resolve($root, $args): int
    {
        $repo = new OrderRepository();

        return $repo->createOrder(
            $args['input']['orderDate'],
            $args['input']['orderTotalAmount'],
            $args['input']['items']
        );
    }
}