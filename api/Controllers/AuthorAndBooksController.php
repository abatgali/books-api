<?php
/**
 * Author:  Adam Wright
 * Date: 5/24/2022
 * Project: books-api
 * USER : awrig
 * DESCRIPTION:
 */


namespace BooksAPI\Controllers;


use BooksAPI\Models\AuthorAndBook;

use Illuminate\Support\Facades\Auth;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use BooksAPI\Controllers\ControllerHelper as Helper;


class AuthorAndBooksController {
    //list all books
    public function index( Request $request, Response $response, array $args) : Response {
        $results = AuthorAndBook::getAuthorsAndBooks();
        return Helper::withJson($response, $results, 200);
    }

    public function view( Request $request, Response $response, array $args) : Response {

        $results = AuthorAndBook::getAuthorAndBookById($args['id']);
        return Helper::withJson($response, $results, 200);
    }

}
