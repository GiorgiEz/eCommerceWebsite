<?php

namespace App\Controller;

use App\GraphQL\Loaders\AttributeLoader;
use App\GraphQL\Loaders\PriceLoader;
use App\GraphQL\Types\SchemaTypes\MutationType;
use App\GraphQL\Types\SchemaTypes\QueryType;
use GraphQL\Error\DebugFlag;
use GraphQL\GraphQL as GraphQLBase;
use GraphQL\Type\Schema;
use GraphQL\Type\SchemaConfig;
use RuntimeException;
use Throwable;


/**
 * Class GraphQL
 *
 * Main class to handle routes.
 */
class GraphQL
{
    /**
     * Main entry method that executes GraphQL queries.
     *
     * @return string
     */
    public static function handle(): string
    {
        try {
            // The GraphQL schema. Schema defines what queries and mutations are available
            $schema = new Schema(
                (new SchemaConfig())
                    ->setQuery(new QueryType())        // attach root Query type
                    ->setMutation(new MutationType())  // attach root Mutation type
            );

            // Read the raw HTTP request body
            $rawInput = file_get_contents('php://input');

            // If body cannot be read, throw an error
            if ($rawInput === false) {
                throw new RuntimeException('Failed to read request body');
            }

            $input = json_decode($rawInput, true);  // Convert JSON request body into PHP array

            // Check that the request actually contains a GraphQL query
            if (!isset($input['query'])) {
                throw new RuntimeException('No GraphQL query provided');
            }

            $query = $input['query'];  // Extract the GraphQL query string
            $variables = $input['variables'] ?? null;  // Optional variables from request

            // GraphQL request context.
            // Loaders are instantiated once per request and shared across resolvers.
            $context = [
                'priceLoader' => new PriceLoader(),
                'attributeLoader' => new AttributeLoader()
            ];

            // Execute the GraphQL query
            $result = GraphQLBase::executeQuery(
                $schema,
                $query,
                null,
                $context,
                $variables
            );

            // Convert execution result into array. Debug flag adds extra error information
            $output = $result->toArray(DebugFlag::INCLUDE_DEBUG_MESSAGE);
        } catch (Throwable $e) {
            // If any exception occurs, return a GraphQL-compatible error response
            $output = [
                'errors' => [
                    [
                        'message' => $e->getMessage()
                    ]
                ]
            ];
        }

        header('Content-Type: application/json; charset=UTF-8');  // Set response header to JSON

        return json_encode($output);
    }
}