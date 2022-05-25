<?php
/**
 * Author:  Adam Wright
 * Date: 5/24/2022
 * Project: books-api
 * USER : awrig
 * DESCRIPTION:
 */

namespace BooksAPI\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use BooksAPI\Models\Genre;
use BooksAPI\Controllers\ControllerHelper as Helper;

class GenreController {

    //list all genres
    public function index( Request $request, Response $response, array $args) : Response {
        $results = Genre::getGenres();
        return Helper::withJson($response, $results, 200);
    }

    public function view( Request $request, Response $response, array $args) : Response {

        $results = Genre::getGenreById($args['id']);
        return Helper::withJson($response, $results, 200);
    }

    //view all books under a given genre
    public function viewBooks(Request $request, Response $response, array $args): Response {
        $id = $args['id'];
        $results = Genre::getBooksByGenre($id);
        return Helper::withJson($response, $results, 200);
    }
}