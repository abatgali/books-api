<?php
/**
 * Author: Anant Batgali
 * Date: 6/5/22
 * File: Token.php
 * Description:
 */

namespace BooksAPI\Models;
use Illuminate\Database\Eloquent\Model;

class Token extends Model {
    //Lifetime of the Bearer token: seconds
    const EXPIRE = 3600;

    /*
     * Generate a Bearer token if it does not exist for the user and store the token in the database.
     * If the token already exists and has not expired, retrieve the token
     */
    public static function generateBearer($id) {
        //Attempt to retrieve the token by user id
        $token = self::where('user', $id)->first();

        //Determine a time in the past: current time minus the lifetime of the token
        $expire = time() - self::EXPIRE;

        //if the token exists and has expired, create a new one
        if($token) {
            if($expire > date_timestamp_get($token->updated_at)) {
                $token->value = bin2hex(random_bytes(64));
                $token->save();
            }
            return $token;
        }

        //A token does not exist; create a new one
        $token = new Token();
        $token->user = $id;
        $token->value = bin2hex(random_bytes(64));
        $token->save();

        return $token;
    }

    //Validate a Bearer token by matching the token with a database record
    public static function validateBearer($value) {
        //Retrieve the token from the database
        $token = self::where('value', $value)->first();

        //Create a time in the past
        $expire = time() - self::EXPIRE;
        return ($token && $expire < date_timestamp_get($token->updated_at)) ? $token : false;
    }
}
