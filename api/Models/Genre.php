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

class Genre extends Model
{
    //the table associated with this model
    protected $table = 'genres';

    //the primary key of the table
    protected $primaryKey = 'genreID';

    //the PK is auto-incremented
    public $incrementing = true;

    //if te updated_at and created_at columns aren't used
    public $timestamps = false;


    // 1-M relationship b/w genre and book models
    public function books()
    {
        return $this->hasMany(Book::class, 'genre_id');
    }

    //retrieve all authors
    public static function getGenres()
    {
        $genres = self::with('books')->get();

        return $genres;
    }

    public static function getGenreById(string $id)
    {
        $genre = self::findOrFail($id);
        $genre->load('books');
        return $genre;
    }

    //genre to books relationship, many books to one genre
    public static function getBooksByGenre(string $id)
    {
        $books = self::findOrFail($id)->books;
        return $books;
    }
}