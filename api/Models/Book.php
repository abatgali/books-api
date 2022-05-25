<?php
/**
* Author: "Emma Parker"
* Date: 5/22/22
* File: Book.php
*Description: define the book model class
*/

namespace BooksAPI\Models;
use Illuminate\Database\Eloquent\Model;



class Book extends Model{

    //the table associated with this model
    protected $table = 'books';

    //the primary key of the table
    protected $primaryKey = 'book_id';

    //the PK is auto-incremented
    public $incrementing = true;

    //if te updated_at and created_at columns aren't used
    public $timestamps = false;

    //retrieve all books
    public static function getBooks(){
    //retrieve all books
    $books = self::all();
    return $books;
    }
        public static function getBookById(string $book_id) {
            $book = self::findOrFail($book_id);
            $book->load('genre');
            return $book;
        }

        // defining 1-M(inverse relationship) b/w genres and books
        public function genre()
        {
            return $this->belongsTo(Genre::class, 'genre_id');
        }

    // Define the many-to-many relationship between Books and Author model classes.
    public function authors(){
        return $this->belongsToMany(Author::class, 'author_and_book', 'book_id', 'author_id');
    }

    //Get a book's authors
    public static function getBookAuthors(string $id) {
        $authors = self::findOrFail($id)->authors;
        return $authors;
    }
}
