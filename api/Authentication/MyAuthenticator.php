<?php
/**
 * Author: "Emma Parker"
 * Date: 6/7/22
 * File: MyAuthenticator.php
 *Description: Define the MyAuthenticator Class
 */

namespace BooksAPI\Authentication;

use Slim\Psr7\Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use BooksAPI\Models\User;

class MyAuthenticator
{
    /*
 * Use the __invoke method so the object can be used as a callable.
 * This method gets called automatically when the object is treated as a callable.
*/
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        //Username and password are stored in a header called "BooksAPI-Authorization".
        if (!$request->hasHeader('BooksAPI-Authorization')) {
            $results = ['Status' => 'BooksAPI-Authorization header not found.'];
            return AuthenticationHelper::withJson($results, 401);
        }
        //Retrieve the header.
        $auth = $request->getHeader('BooksAPI-Authorization');

        $apikey = $auth[0];
        list($username, $password) = explode(':', $apikey);

        //Retrieve the header and then the username and password
        $auth = $request->getHeader('BooksAPI-Authorization');
        list($username, $password) = explode(':', $auth[0]);
        //Validate the username and password
        if (!User::authenticateUser($username, $password)) {
            $results = ['Status' => 'Authentication failed.'];
            return AuthenticationHelper::withJson($results, 403);
        }
//A user has been authenticated
        return $handler->handle($request);


    }

}