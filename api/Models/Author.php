<?php
/**
 * Author:  Adam Wright
 * Date: 5/23/2022
 * Project: books-api
 * USER : awrig
 * DESCRIPTION:
 */


namespace BooksAPI\Models;
use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
//the table associated with this model
    protected $table = 'authors';

//the primary key of the table
    protected $primaryKey = 'author_id';

//the PK is auto-incremented
    public $incrementing = true;

//if te updated_at and created_at columns aren't used
    public $timestamps = false;

//retrieve all authors
    public static function getAuthors()
    {
//retrieve all authors
        $authors = self::all();
        return $authors;
    }

    public static function getAuthorById(string $id)
    {
        $author = self::findOrFail($id);
        return $author;

    }

    // Define the many-to-many relationship between Books and Author model classes.
    public function books(){
        return $this->belongsToMany(Book::class, 'author_and_book', 'book_id', 'author_id');
    }

    //Get an author's books
    public static function getAuthorBooks(string $id) {
        $books = self::findOrFail($id)->books;
        return $books;
    }

}