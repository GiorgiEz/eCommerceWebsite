<?php

namespace App\GraphQL\Types\SchemaTypes;

use GraphQL\Type\Definition\ObjectType;

class MutationType extends ObjectType
{
    public function __construct()
    {
        parent::__construct([
            'name' => 'Mutation',
            'fields' => [
                'createOrder' => 'placeholder'
            ]
        ]);
    }
}