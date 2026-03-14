<?php

namespace App\GraphQL\Types;

use GraphQL\Type\Definition\ObjectType;

/**
 * Base class providing singleton behavior for GraphQL types.
 */
abstract class BaseType extends ObjectType
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