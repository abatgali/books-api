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
use BooksAPI\Validation\Validator;

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

    //Create a genre
    public function create(Request $request, Response $response, array $args) : Response {

        //Validate the request
        $validation = Validator::validateGenre($request);

        if(!$validation) {
            $results = [
                'status' => "Validation failed",
                'errors' => Validator::getErrors() ];

            return Helper::withJson($response, $results, 500);
        }

        //Create a new student
        $genre = Genre::createGenres($request);

        if(!$genre) {
            $results['status']= "Genre can't be inserted.";
            return Helper::withJson($response, $results, 500);
        }

        $results = [
            'status' => "Genre added.",
            'data' => $genre
        ];

        return Helper::withJson($response, $results, 201);
    }

    //Update a genre
    public function update(Request $request, Response $response, array $args) : Response {
        //Validate the request
        $validation = Validator::validateGenre($request);
        //if validation failed
        if(!$validation) {
            $results = [
                'status' => "Validation failed",
                'errors' => Validator::getErrors()
            ];
            return Helper::withJson($response, $results, 500);
        }
        $genre = Genre::updateGenre($request);
        if(!$genre) {
            $results['status']= "Genre can't be updated.";
            return Helper::withJson($response, $results, 500);
        }
        $results = [
            'status' => "Genre updated.",
            'data' => $genre
        ];

        return Helper::withJson($response, $results, 200);
    }

    //Delete a genre
    public function delete(Request $request, Response $response, array $args) : Response {
        $genre = Genre::deleteGenre($request);

        if(!$genre) {
            $results['status']= "Genre can't be deleted.";
            return Helper::withJson($response, $results, 500);
        }

        $results['status'] = "Genre deleted.";
        return Helper::withJson($response, $results, 200);
    }
}