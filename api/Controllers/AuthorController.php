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

    //View all classes of a student
    public function viewAuthorBooks(Request $request, Response $response, array $args) : Response {
        $id = $args['id'];
        $results = Author::getAuthorBooks($id);
        return Helper::withJson($response, $results, 200);
    }
}