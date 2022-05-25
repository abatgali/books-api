<?php
/**
 * Author:  Adam Wright
 * Date: 5/24/2022
 * Project: books-api
 * USER : awrig
 * DESCRIPTION:
 */


namespace BooksAPI\Models;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
//the table associated with this model
    protected $table = 'ratings';

//the primary key of the table
    protected $primaryKey = 'rating_id';

//the PK is auto-incremented
    public $incrementing = true;

//if te updated_at and created_at columns aren't used
    public $timestamps = false;

//retrieve all authors
    public static function getRatings()
    {
//retrieve all authors
        $ratings = self::all();
        return $ratings;
    }

    public static function getGenreById(string $id)
    {
        $rating = self::findOrFail($id);
        return $rating;


    }

}