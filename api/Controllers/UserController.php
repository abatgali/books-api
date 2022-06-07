<?php

/**
 * Author:  Adam Wright
 * Date: 6/5/2022
 * Project: mycollege-api
 * USER : awrig
 * DESCRIPTION:
 */

namespace BooksAPI\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use BooksAPI\Controllers\ControllerHelper as Helper;
use BooksAPI\Validation\Validator;
use BooksAPI\Models\User;
use BooksAPI\Models\Token;

class UserController
{


// List users
    public function index(Request $request, Response $response, array $args): Response
    {
        $results = User::getUsers();
        return Helper::withJson($response, $results, 200);
    }

// View a specific user by its id
    public function view(Request $request, Response $response, array $args): Response
    {
        $results = User::getUserById($request->getAttribute('id'));
        return Helper::withJson($response, $results, 200);
    }

// Create a user when the user signs up an account
    public function create(Request $request, Response $response, array $args): Response
    {
        // Validate the request
        $validation = Validator::validateUser($request);

        // If validation failed
        if (!$validation) {
            $results = [
                'status' => "Validation failed",
                'errors' => Validator::getErrors()
            ];
            return Helper::withJson($response, $results, 500);
        }

        // Validation has passed; Proceed to create the user
        $user = User::createUser($request);

        if (!$user) {
            $results['status'] = "User cannot been created.";
            return Helper::withJson($response, $results, 500);
        }

        $results = [
            'status' => 'User has been created',
            'data' => $user
        ];
        return Helper::withJson($response, $results, 201);
    }

// Update a user
    public function update(Request $request, Response $response, array $args): Response
    {
        // Validate the request
        $validation = Validator::validateUser($request);

        // If validation failed
        if (!$validation) {
            $results = [
                'status' => "Validation failed",
                'errors' => Validator::getErrors()
            ];
            return Helper::withJson($response, $results, 500);
        }

        //Validation has passed, proceed to update the user
        $user = User::updateUser($request);

        //If update has been failed
        if (!$user) {
            $results['status'] = "User cannot been updated.";
            return Helper::withJson($response, $results, 500);
        }

        //Update was successful, send the confirmation
        $results = [
            'status' => "User has been updated.",
            'data' => $user
        ];

        return Helper::withJson($response, $results, 200);
    }

// Delete a user
    public function delete(Request $request, Response $response, array $args): Response
    {
        $user = User::deleteUser($request);

        if (!$user) {
            $results['status'] = "User cannot been deleted.";
            return Helper::withJson($response, $results, 500);
        }

        $results['status'] = "User has been deleted.";
        return Helper::withJson($response, $results, 200);
    }

    // Validate a user’s username and password.
    // Return a Bearer token on success or error on failure.
    public function authBearer(Request $request, Response $response, array $args):Response {
        //Retrieve username and password from the request body
        $params = $request->getParsedBody();
        $username = $params['username'];
        $password = $params['password'];

        //Verify username and password
        $user = User::authenticateUser($username, $password);
        if(!$user) {
            return Helper::withJson($response, ['Status' => 'Login failed.'], 401);
        }

        //Username and password are valid
        $token = Token::generateBearer($user->id);
        $results = ['Status' => 'Login successful', 'Token' => $token];

        return Helper::withJson($response, $results, 200);
    }
}