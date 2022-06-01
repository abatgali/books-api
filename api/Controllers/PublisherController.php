<?php
/**
 * Author: Anant Batgali
 * Date: 5/24/22
 * File: PublisherController.php
 * Description:
 */


/**
 * Author: Anant Batgali
 * Date: 5/24/22
 * File: PublisherController.php
 * Description: handles all requests per the
 */

namespace BooksAPI\Controllers;

use BooksAPI\Models\Publisher;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use BooksAPI\Controllers\ControllerHelper as Helper;
use BooksAPI\Validation\Validator;


class PublisherController
{

    //list all publishers
    public function index(Request $request, Response $response, array $args): Response
    {
        $results = Publisher::getPublishers();
        return Helper::withJson($response, $results, 200);
    }

    //list a specific publisher
    public function view(Request $request, Response $response, array $args): Response
    {

        $results = Publisher::getPublisherById($args['id']);
        return Helper::withJson($response, $results, 200);
    }

    //Create a publisher
    public function create(Request $request, Response $response, array $args) : Response {

        //Validate the request
        $validation = Validator::validatePublisher($request);

        if(!$validation) {
            $results = [
                'status' => "Validation failed",
                'errors' => Validator::getErrors() ];

            return Helper::withJson($response, $results, 500);
        }

        //Create a new publisher
        $publisher = Publisher::createPublishers($request);

        if(!$publisher) {
            $results['status']= "Publisher can't be inserted.";
            return Helper::withJson($response, $results, 500);
        }

        $results = [
            'status' => "Publisher added.",
            'data' => $publisher
        ];

        return Helper::withJson($response, $results, 201);
    }
}