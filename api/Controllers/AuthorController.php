<?php
/**
 * Author:  Adam Wright
 * Date: 5/23/2022
 * Project: books-api
 * USER : awrig
 * DESCRIPTION:
 */

namespace BooksAPI\Controllers;

use BooksAPI\Models\Author;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use BooksAPI\Controllers\ControllerHelper as Helper;
use BooksAPI\Validation\Validator;

class AuthorController {
    //list all books
    public function index( Request $request, Response $response, array $args) : Response {
        //$results = Author::getAuthors();
        //Get querystring variables from url
        $params = $request->getQueryParams();
        $term = array_key_exists('q', $params) ? $params['q'] : "";

        //Call the model method to get authors
        $results = ($term) ? Author::searchAuthors($term) : Author::getAuthors();
        return Helper::withJson($response, $results, 200);
    }

    public function view( Request $request, Response $response, array $args) : Response {

        $results = Author::getAuthorById($args['id']);
        return Helper::withJson($response, $results, 200);
    }

    //View all classes of a author
    public function viewAuthorBooks(Request $request, Response $response, array $args) : Response {
        $id = $args['id'];
        $results = Author::getAuthorBooks($id);
        return Helper::withJson($response, $results, 200);
    }

    //Create a author
    public function create(Request $request, Response $response, array $args) : Response {

        //Validate the request
        $validation = Validator::validateAuthor($request);

        if(!$validation) {
            $results = [
                'status' => "Validation failed",
                'errors' => Validator::getErrors() ];

            return Helper::withJson($response, $results, 500);
        }

        //Create a new author
        $author = Author::createAuthors($request);

        if(!$author) {
            $results['status']= "Author cannot be inserted.";
            return Helper::withJson($response, $results, 500);
        }

        $results = [
            'status' => "Author added.",
            'data' => $author
        ];

        return Helper::withJson($response, $results, 201);
    }
//Update a author
    public function update(Request $request, Response $response, array $args) : Response {
        //Validate the request
        $validation = Validator::validateAuthor($request);
        //if validation failed
        if(!$validation) {
            $results = [
                'status' => "Validation failed",
                'errors' => Validator::getErrors()
            ];
            return Helper::withJson($response, $results, 500);
        }
        $author = Author::updateAuthor($request);
        if(!$author) {
            $results['status']= "Author cannot been updated.";
            return Helper::withJson($response, $results, 500);
        }
        $results = [
            'status' => "Author has been updated.",
            'data' => $author
        ];
        return Helper::withJson($response, $results, 200);
    }


}