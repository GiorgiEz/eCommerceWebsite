<?php

namespace App\GraphQL\Queries;

use App\Database\Repository\CategoryRepository;
use App\GraphQL\Types\ModelTypes\CategoryModelType;
use GraphQL\Type\Definition\Type;

/**
 * GraphQL query for fetching all categories.
 *
 * Allows clients to request a list of all categories.
 */
class CategoryQuery
{
    /**
     * Returns the query configuration for the schema.
     */
    public static function build(): array
    {
        return [
            'type' => Type::listOf(CategoryModelType::get()),
            'resolve' => function () {
                $repo = new CategoryRepository();
                return $repo->getAll();
            }
        ];
    }
}