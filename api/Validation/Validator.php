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

    //Validate book data.
    public static function validateBook($request) : bool {
        //Define all the validation rules
        $rules = [
            'title' => v::charset('ASCII'),
            'isbn' => v::isbn(),
            'publisher_id' => v::number(),
            // price value has to be enclosed within double quotation marks when posting
            'price' => v::decimal(2),
            'description' => v::charset('ASCII'),
            'image' => v::Url(),
            'genre_id' => v::number(),
            'rating_id' => v::number()
        ];

        return self::validate($request, $rules);
    }

    //Validate author data.
    public static function validateAuthor($request) : bool {
        //Define all the validation rules
        $rules = [
            'firstname' => v::alpha(),
            'lastname' => v::alpha()
        ];

        return self::validate($request, $rules);
    }

    //Validate publisher data.
    public static function validatePublisher($request) : bool {
        //Define all the validation rules
        $rules = [
            'publisher_id' => v::number(),
            'publisher_name' => v::alpha(),
            'address' => v::charset('ASCII'),
            'website' => v::url()
        ];

        return self::validate($request, $rules);
    }

    // Validate Genre data
    public static function validateGenre($request) : bool {
        //Define all the validation rules
        $rules = [
            'fiction_nonfiction' => v::alpha(),
            'genre_name' => v::alpha(' &')
        ];

        return self::validate($request, $rules);
    }

}