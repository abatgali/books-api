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
    public function index(Response $response, array $args) : Response {
        $results = Book::getBooks();
        return Helper::withJson($response, $results, 200);
    }

    public function view(Response $response, array $args) : Response {
        $book_id = $args['book_id'];
        $results = Book::getBookById($book_id);
       return Helper::withJson($response, $results, 200);
    }

}