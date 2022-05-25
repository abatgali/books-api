<?php
/**
 * Author: "Emma Parker"
 * Date: 5/22/22
 * File: BookController.php
 *Description: define the book controller class
 */

namespace BooksAPI\Controllers;


use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use BooksAPI\Models\Book;
use BooksAPI\Controllers\ControllerHelper as Helper;
class BookController {
    //list all books
    public function index( Request $request, Response $response, array $args) : Response {
        $results = Book::getBooks();
        return Helper::withJson($response, $results, 200);
    }

    public function view( Request $request, Response $response, array $args) : Response {

        $results = Book::getBookById($args['id']);
       return Helper::withJson($response, $results, 200);
    }

    //View all classes of a student
    public function viewBookAuthors(Request $request, Response $response, array $args) : Response {
        $id = $args['id'];
        $results = Book::getBookAuthors($id);
        return Helper::withJson($response, $results, 200);
    }
}