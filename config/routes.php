<?php
/**
 * Author: "Emma Parker"
 * Date: 5/22/22
 * File: routes.php
 *Description:
 */
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Routing\RouteCollectorProxy;

return function(App $app){
    // Define app route
    $app->get('/', function (Request $request, Response $response, array $args) { $response->getBody()->write('Welcome to Books API!');
        return $response;
    });
// Add another route
    $app->get('/api/hello/{name}', function (Request $request, Response $response, array $args) {
        $response->getBody()->write("Hello " . $args['name']);
        return $response;
    });

    //Route group for api/v1 pattern
    $app->group('/api/v1', function(RouteCollectorProxy $group) {
        //Route group for books pattern
        $group->group('/books', function (RouteCollectorProxy $group){
            $group->get('', 'Book:index');
            $group->get('/{id}', 'Book:view');

        });
        //Route group for Authors pattern
        $group->group('/authors', function (RouteCollectorProxy $group){
            $group->get('','Author:index');
            $group->get('/{id}','Author:view');
        });

        //Route group for Genres pattern (all genres works but not ID

        $group->group('/genres', function (RouteCollectorProxy $group){
            $group->get('','Genre:index');
            $group->get('/{id}','Genre:view');
            $group->get('/{id}/books', 'Genre::getBooksByGenre');

        });

        //Route group for Authors and Books pattern
        $group->group('/authors_and_books', function (RouteCollectorProxy $group){
            $group->get('','AuthorAndBook:index');
            $group->get('/{id}','AuthorAndBook:view');
        });

        //Route group for Publishers pattern
        $group->group('/publishers', function (RouteCollectorProxy $group){
            $group->get('','Publisher:index');
            $group->get('/{id}','Publisher:view');
        });
    });
};

