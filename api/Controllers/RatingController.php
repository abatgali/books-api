<?php
/**
 * Author:  Adam Wright
 * Date: 5/24/2022
 * Project: books-api
 * USER : awrig
 * DESCRIPTION:
 */


namespace BooksAPI\Controllers;

use BooksAPI\Models\Rating;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

use BooksAPI\Controllers\ControllerHelper as Helper;
class RatingController{
//view all ratings

    public function index( Request $request, Response $response, array $args) : Response {
        $results = Rating::getRatings();
        return Helper::withJson($response, $results, 200);
    }
//view specific Rating
    public function view( Request $request, Response $response, array $args) : Response {

        $results = Rating::getRatingById($args['id']);

        return Helper::withJson($response, $results, 200);
    }


}