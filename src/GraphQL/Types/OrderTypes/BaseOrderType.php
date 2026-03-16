<?php

namespace App\GraphQL\Types\OrderTypes;

use GraphQL\Type\Definition\InputObjectType;

/**
 * Base class providing singleton behavior for GraphQL Order types.
 */
abstract class BaseOrderType extends InputObjectType
{
    /**
     * Stores singleton instances per type class.
     */
    private static array $instances = [];

    /**
     * Returns the singleton instance of the called type.
     */
    public static function get(): static
    {
        $class = static::class;

        if (!isset(self::$instances[$class])) {
            self::$instances[$class] = new static();
        }

        return self::$instances[$class];
    }
}