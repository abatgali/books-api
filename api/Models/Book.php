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
    public static function getBooks($request){

        /*********** code for pagination and sorting *************************/

        //get the total number of row count
        $count = self::count();

        //Get querystring variables from url
        $params = $request->getQueryParams();

        //do limit and offset exist?
        $limit = array_key_exists('limit', $params) ? (int)$params['limit'] : 5;   //items per page
        $offset = array_key_exists('offset', $params) ? (int)$params['offset'] : 0;  //offset of the first item

        //pagination
        $links = self::getLinks($request, $limit, $offset);

        //build the query to get all related data
        $query = self::with(['authors', 'genre', 'rating', 'publisher']);
        $query = $query->skip($offset)->take($limit);  //limit the rows

        //code for sorting
        $sort_key_array = self::getSortKeys($request);

        //soft the output by one or more columns
        foreach ($sort_key_array as $column => $direction) {
            $query->orderBy($column, $direction);
        }

        //retrieve the books
        $books = $query->get();  //Finally, run the query and get the results

        //construct the data for response
        $results = [
            'totalCount' => $count,
            'limit' => $limit,
            'offset' => $offset,
            'links' => $links,
            'sort' => $sort_key_array,
            'data' => $books
        ];

        return $results;

    }

    // retrieve a specific book
    public static function getBookById(string $book_id) {
        $book = self::findOrFail($book_id);
        $book->load('genre');
        $book->load('rating');
        $book->load('publisher');
        $book->load('authors');

        return $book;
    }

    // defining 1-M(inverse relationship) b/w genres and books
    public function genre()
    {
        return $this->belongsTo(Genre::class, 'genre_id');
    }

    // defining 1-M(inverse relationship) b/w genres and books
    public function rating()
    {
        return $this->belongsTo(Rating::class, 'rating_id');
    }

    // defining 1-M(inverse relationship) b/w genres and books
    public function publisher()
    {
        return $this->belongsTo(Publisher::class, 'publisher_id');
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

    public static function searchBooks($term)
    {
        if (is_numeric($term)) {
            $query = self::where('book_id', '=', $term);
        } else {
            $query = self::where('title', 'like', "%$term%")
                ->orWhere('description', 'like', "%$term%")
                ->orWhere('isbn', 'like', "%$term%");
        }
        return $query->get();
    }

    //Insert a new book
    public static function createBooks($request) {

        //Retrieve parameters from request body
        $params = $request->getParsedBody();
        //Create a new book instance
        $book = new Book();

        //Set the book's attributes
        foreach($params as $field => $value) {
            $book->$field = $value;
        }

        //Insert the book into the database
        $book->save();

        return $book;
    }
    //Update a book
    public static function updateBook($request) {
        //Retrieve parameters from request body
        $params = $request->getParsedBody();
        //Retrieve id from the request url
        $id = $request->getAttribute('id');
        $book = self::findOrFail($id);
        if(!$book) {
            return false;
        }
        //update attributes of the book
        foreach($params as $field => $value) {
            $book->$field = $value;
        }
        //save the book into the database
        $book->save();
        return $book;
    }

    //Delete a book
         public static function deleteBook($request) {
             //Retrieve id from the request
             $id = $request->getAttribute('id');
             $book = self::findOrFail($id);
             return($book ? $book->delete() : $book);
    }

    // Return an array of links for pagination. The array includes links for the current, first, next, and last pages.
    private static function getLinks($request, $limit, $offset) {
        $count = self::count();

        // Get request uri and parts
        $uri = $request->getUri();
        if($port = $uri->getPort()) {
            $port = ':' . $port;
        }
        $base_url = $uri->getScheme() . "://" . $uri->getHost() . $port . $uri->getPath();

        // Construct links for pagination
        $links = [];
        $links[] = ['rel' => 'self', 'href' => "$base_url?limit=$limit&offset=$offset"];
        $links[] = ['rel' => 'first', 'href' => "$base_url?limit=$limit&offset=0"];
        if ($offset - $limit >= 0) {
            $links[] = ['rel' => 'prev', 'href' => "$base_url?limit=$limit&offset=" . $offset - $limit];
        }
        if ($offset + $limit < $count) {
            $links[] = ['rel' => 'next', 'href' => "$base_url?limit=$limit&offset=" . $offset + $limit];
        }
        $links[] = ['rel' => 'last', 'href' => "$base_url?limit=$limit&offset=" . $limit * (ceil($count / $limit) - 1)];

        return $links;
    }

    /*
     * Sort keys are optionally enclosed in [ ], separated with commas;
     * Sort directions can be optionally appended to each sort key, separated by :.
     * Sort directions can be 'asc' or 'desc' and defaults to 'asc'.
     * Example: sort=[title:asc,price:desc]
     * This function retrieves sorting keys from uri and returns an array.
    */
    private static function getSortKeys($request) {
        $sort_key_array = [];

        // Get querystring variables from url
        $params = $request->getQueryParams();

        if (array_key_exists('sort', $params)) {
            $sort = preg_replace('/^\[|\]$|\s+/', '', $params['sort']);

            // remove white spaces, [, and ]
            $sort_keys = explode(',', $sort);

            //get all the key:direction pairs
            foreach ($sort_keys as $sort_key) {
                $direction = 'asc';
                $column = $sort_key;
                if (strpos($sort_key, ':')) {
                    list($column, $direction) = explode(':', $sort_key);
                }
                $sort_key_array[$column] = $direction;
            }
        }

        return $sort_key_array;
    }
}


