<?php
/**
 *Author: your name*
 * Date: 6/8/2022
 * File: JWTAuthenticator.php
 * Description:
 */

namespace MyCollegeAPI\Authentication;
use Slim\Psr7\Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use MyCollegeAPI\Models\User;

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