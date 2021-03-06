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
use BooksAPI\Authentication\{
    MyAuthenticator,
    BasicAuthenticator,
    BearerAuthenticator,
    JWTAuthenticator
};

return function(App $app){

    //Set up CORS (Cross-Origin Resource Sharing) https://www.slimframework.com/docs/v4/cookbook/enable-cors.html
    $app->options('/{routes:.+}', function ($request, $response, $args) {
        return $response;
    });

    $app->add(function ($request, $handler) {
        $response = $handler->handle($request);
        return $response
            ->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
    });

    // Define app route
    $app->get('/', function (Request $request, Response $response, array $args) {
        $response->getBody()->write('Welcome to Books API!');
        return $response;
    });

    //Route group for Users pattern
    $app->group('/api/v1/users', function (RouteCollectorProxy $group) {
        $group->get('', 'User:index');
        $group->get('/{id}', 'User:view');
        $group->post('', 'User:create');
        $group->put('/{id}', 'User:update');
        $group->delete('/{id}', 'User:delete');

        // route for authenticating a user with Bearer token
        $group->post('/authBearer', 'User:authBearer');

        // route for JWT authentication
        $group->post('/authJWT', 'User:authJWT');
    });

    //Route group for api/v1 pattern
    $app->group('/api/v1', function(RouteCollectorProxy $group) {

        //Route group for books pattern
        $group->group('/books', function (RouteCollectorProxy $group) {
            $group->get('', 'Book:index');
            $group->get('/{id}', 'Book:view');
            $group->get('/{id}/authors', 'Book:viewBookAuthors');
            $group->post('', 'Book:create');
            $group->put('/{id}', 'Book:update');
            $group->delete('/{id}', 'Book:delete');
        });

        //Route group for Authors pattern
        $group->group('/authors', function (RouteCollectorProxy $group) {
            $group->get('', 'Author:index');
            $group->get('/{id}', 'Author:view');
            $group->get('/{id}/books', 'Author:viewAuthorBooks');
            $group->post('', 'Author:create');
            $group->put('/{id}', 'Author:update');
            $group->delete('/{id}', 'Author:delete');
        });

        //Route group for Genres pattern
        $group->group('/genres', function (RouteCollectorProxy $group) {
            $group->get('', 'Genre:index');
            $group->get('/{id}', 'Genre:view');
            $group->get('/{id}/books', 'Genre:viewBooks');
            $group->post('', 'Genre:create');
            $group->put('/{id}', 'Genre:update');
            $group->delete('/{id}', 'Genre:delete');
        });

        //Route group for Authors and Books pattern
        $group->group('/authors_and_books', function (RouteCollectorProxy $group) {
            $group->get('', 'AuthorAndBook:index');
            $group->get('/{id}', 'AuthorAndBook:view');
        });

        //Route group for Publishers pattern
        $group->group('/publishers', function (RouteCollectorProxy $group) {
            $group->get('', 'Publisher:index');
            $group->get('/{id}', 'Publisher:view');
            $group->post('', 'Publisher:create');
            $group->put('/{id}', 'Publisher:update');
            $group->delete('/{id}', 'Publisher:delete');
        });

        //Route group for Ratings pattern
        $group->group('/ratings', function (RouteCollectorProxy $group) {
            $group->get('', 'Rating:index');
            $group->get('/{id}', 'Rating:view');
        });
    //});   //No auth
        //})->add(new MyAuthenticator());  //MyAuthentication
        //})->add(new BasicAuthenticator());
        //})->add(new BearerAuthenticator());
    })->add(new JWTAuthenticator());

};

