<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;
use App\Controller\GraphQL as GraphQL;

/*
| CORS HEADERS
*/
$allowedOrigins = [
    "http://localhost:5173"
];

$origin = $_SERVER['HTTP_ORIGIN'] ?? '';

if (in_array($origin, $allowedOrigins)) {
    header("Access-Control-Allow-Origin: $origin");
}
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Content-Type: application/json; charset=UTF-8");

/*
Handle browser preflight request
*/
$method = $_SERVER['REQUEST_METHOD'];

if (!in_array($method, ['GET', 'POST', 'OPTIONS'])) {
    http_response_code(405);
    exit;
}

if ($method === 'OPTIONS') {
    http_response_code(200);
    exit;
}

if ($method === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        http_response_code(400);
        echo json_encode(["error" => "Invalid JSON"]);
        exit;
    }
}

set_exception_handler(function ($e) {
    http_response_code(500);
    echo json_encode([
        "error" => "Internal Server Error"
    ]);
});

// Load environment variables from .env file. __DIR__ = /public, so ../ points to project root
$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

//Create a FastRoute dispatcher (router).  It defines which URL routes map to which handlers
$dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) {
    // Register POST /graphql route. Requests sent to /graphql will call GraphQL::handle()
    $r->post('/graphql', [GraphQL::class, 'handle']);
});

// Get the request URI (Uniform Resource Identifier) from the server (e.g. /eCommerceWebsite/public/graphql)
$uri = $_SERVER['REQUEST_URI'];

// Remove query string if present (e.g. ?id=1)
$pos = strpos($uri, '?');
if ($pos !== false) {
    $uri = substr($uri, 0, $pos);
}

// Decode URL-encoded characters
$uri = rawurldecode($uri);

// Detect base path of the project dynamically
$basePath = dirname($_SERVER['SCRIPT_NAME']);

// Remove the base path from the URI so the router sees only /graphql
if ($basePath !== '/' && str_starts_with($uri, $basePath)) {
    $uri = substr($uri, strlen($basePath));
}

// Dispatch the request to the router. It checks request method and path and finds the matching route
$routeInfo = $dispatcher->dispatch($method, $uri);

// status - 0 or 1.
// handler - (GraphQL::handle) function
// vars - Variables extracted from the route (not used here but required by FastRoute)
[$status, $handler, $vars] = $routeInfo;
switch ($status) {
    case FastRoute\Dispatcher::NOT_FOUND:    // No route matched
        http_response_code(404);
        echo "Route not found";
        break;

    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:    // Route exists but HTTP method is incorrect (GET instead of POST)
        http_response_code(405);
        echo "Method not allowed";
        break;

    case FastRoute\Dispatcher::FOUND:    // Route matched successfully
        $response = $handler($vars);

        if (is_array($response)) {
            echo json_encode($response);
        } else {
            echo $response;
        }
        break;
}