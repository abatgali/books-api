<?php
/**
 * Author: Anant Batgali
 * Date: 6/8/22
 * File: JWTAuthenticator.php
 * Description:
 */

namespace BooksAPI\Authentication;
use Slim\Psr7\Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use BooksAPI\Models\User;

class JWTAuthenticator {

    public function __invoke(Request $request, RequestHandler $handler) : Response {
        //If the header named "Authorization" does not exist, returns an error
        if(!$request->hasHeader('Authorization')) {
            $results = ['Status' => 'Authorization header not available'];
            return AuthenticationHelper::withJson($results, 401);
        }

        //Retrieve the header and the token
        $auth = $request->getHeader('Authorization');
        list(, $token) = explode(" ", $auth[0], 2);

        //Validate the token
        if(!User::validateJWT($token)) {
            $results = ['Status' => 'Authentication failed.'];
            return AuthenticationHelper::withJson($results, 403);
        }

        //Authentication succeeded
        return $handler->handle($request);
    }
}