<?php
/**
 * Author:  Adam Wright
 * Date: 5/24/2022
 * Project: books-api
 * USER : awrig
 * DESCRIPTION:
 */


/**
 * Author:  Adam Wright
 * Date: 5/23/2022
 * Project: books-api
 * USER : awrig
 * DESCRIPTION:
 */


namespace BooksAPI\Models;

use Illuminate\Database\Eloquent\Model;

class AuthorAndBook extends Model
{
//the table associated with this model
    protected $table = 'author_and_book';

//the primary key of the table
    protected $primaryKey = 'author_book_id';

//the PK is auto-incremented
    public $incrementing = true;

//if te updated_at and created_at columns aren't used
    public $timestamps = false;

//retrieve all authors
    public static function getAuthorsAndBooks()
    {
//retrieve all authors
        $author_and_books = self::all();
        return $author_and_books;
    }

    public static function getAuthorAndBookById(string $id)
    {
        $authors_and_book = self::findOrFail($id);
        return $authors_and_book;


    }


}