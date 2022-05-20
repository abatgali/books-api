<?php
/**
 * Author:Adam Wright
 * Date: 5/20/2022
 * File:Index
 */


use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

// Instantiate app
$app = AppFactory::create();
//Set basepath
$app->setBasePath('/Documents/GitHub/books-api');
// Add middleware for parsing JSON, form data and xml
$app->addBodyParsingMiddleware();
// Add the Slim built-in routing middleware
$app->addRoutingMiddleware();

// Add Error Handling Middleware
$app->addErrorMiddleware(true, true, true);
// Define app route
$app->get('/', function (Request $request, Response $response, array $args) {
    $response->getBody()->write('Welcome to MyCollege API!');
    return $response;
});
// Run an app route
$app->run();
