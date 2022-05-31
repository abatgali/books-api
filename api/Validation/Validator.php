<?php
/**
 * Author: Anant Batgali
 * Date: 5/31/22
 * File: Validator.php
 * Description: to check new resources against the constraints before inserting into the database
 */

namespace BooksAPI\Validation;

use Respect\Validation\Validator as v;
use Respect\Validation\Exceptions\NestedValidationException;


class Validator
{
    private static array $errors = [];

    //Return the errors in an array
    public static function getErrors() : array {
        return self::$errors;
    }

    // A generic validation method. it returns true on success or false on failed validation.
    public static function validate($request, array $rules) : bool {
        foreach ($rules as $field => $rule) {
            //Retrieve parameters from URL or the request body
            $param = $request->getAttribute($field) ?? $request->getParsedBody()[$field];
            try{
                $rule->setName($field)->assert($param);
            } catch (NestedValidationException $ex) {
                self::$errors[$field] = $ex->getFullMessage();
            }
        }
        // Return true or false; "false" means a failed validation.
        return empty(self::$errors);
    }

    //Validate student data.
    public static function validateBook($request) : bool {
        //Define all the validation rules
        $rules = [
            //'id' => v::notEmpty()->alnum()->startsWith('s')->length(5, 5),
            'title' => v::alnum(' '),
            'isbn' => v::isbn(),
            'publisher_id' => v::number(),
            'price' => v::decimal(2),
            'description' => v::alnum(),
            'image' => v::Url(),
            'genre_id' => v::number(),
            'rating_id' => v::number()
        ];

        return self::validate($request, $rules);
    }
}