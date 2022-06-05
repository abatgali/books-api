<?php
/**
 * Author:  Adam Wright
 * Date: 6/5/2022
 * Project: books-api
 * USER : awrig
 * DESCRIPTION:
 */

namespace BooksAPI\Models;
use Illuminate\Database\Eloquent\Model;


class User extends Model {
//The table associated with this model. "users" is the default name.
    protected $table = 'users';
//The primary key of the table. "id" is the default name.
    protected $primaryKey = 'id';
//Is the PK an incrementing integer value? "True" is the default value.
    public $incrementing = true;
//The data type of the PK. "int" is the default value.
    protected $keyType = 'int';
//Do the created_at and updated_at columns exist in the table? "True" is the default
//value.
    public $timestamps = true;
//List all users
    public static function getUsers() {
        $users = self::all();
        return $users;
    }


    // View a specific user by id
    public static function getUserById(string $id)
    {
        $user = self::findOrFail($id);
        return $user;
    }

    // Create a new user
    public static function createUser($request)
    {
        // Retrieve parameters from request body
        $params = $request->getParsedBody();

        // Create a new User instance
        $user = new User();

        // Set the user's attributes
        foreach ($params as $field => $value) {
            $user->$field = ($field == "password") ? password_hash($value, PASSWORD_DEFAULT) : $value;
        }

        // Insert the user into the database
        $user->save();
        return $user;
    }

    // Update a user
    public static function updateUser($request)
    {
        // Retrieve parameters from request body
        $params = $request->getParsedBody();

        //Retrieve the user's id from url
        $id = $request->getAttribute('id');
        $user = self::findOrFail($id);

        if(!$user) {
            return false;
        }

        //update attributes of the user
        foreach($params as $field => $value) {
            $user->$field =  ($field == "password") ? password_hash($value, PASSWORD_DEFAULT) : $value;
        }

        // Update the user
        $user->save();
        return $user;
    }

    // Delete a user
    public static function deleteUser($request)
    {
        $user = self::findOrFail($request->getAttribute('id'));
        return ($user ? $user->delete() : $user);
    }


}