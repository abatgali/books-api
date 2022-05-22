<?php
/**
* Author: "Emma Parker"
* Date: 5/22/22
* File: Book.php
*Description: define the book model class
*/

namespace BooksAPI\Models;
use Illuminate\Database\Eloquent\Model;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

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
        return $book;
    }


}
