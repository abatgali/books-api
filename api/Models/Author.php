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
    public static function getAuthors($request)
    {
//        -----Canceled out following code to ake room for pagination & sorting -----
//retrieve all authors
//        $authors = self::all();
//        $authors = self::with("books")->get();
//        return $authors;
//        /*********** code for pagination and sorting *************************/
        //get the total number of row count
        $count = self::count();

        //Get querystring variables from url
        $params = $request->getQueryParams();

        //do limit and offset exist?
        $limit = array_key_exists('limit', $params) ? (int)$params['limit'] : 5;   //items per page
        $offset = array_key_exists('offset', $params) ? (int)$params['offset'] : 0;  //offset of the first item

        //pagination
        $links = self::getLinks($request, $limit, $offset);

        //build query
        $query = self::with('books');  //build the query to get all authors
        $query = $query->skip($offset)->take($limit);  //limit the rows


        //code for sorting
        $sort_key_array = self::getSortKeys($request);

        //soft the output by one or more columns
        foreach ($sort_key_array as $column => $direction) {
            $query->orderBy($column, $direction);
        }


        //retrieve the authors
        $authors = $query->get();  //Finally, run the query and get the results

        //construct the data for response
        $results = [
            'totalCount' => $count,
            'limit' => $limit,
            'offset' => $offset,
            'links' => $links,
            'sort' => '$sort_key_array',
            'data' => $authors,
        ];

        return $results;

    }

    public static function getAuthorById(string $id)
    {
        $author = self::findOrFail($id);
        $author->load("");
        return $author;

    }
//--------
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
    //------
    /*
     * Sort keys are optionally enclosed in [ ], separated with commas;
     * Sort directions can be optionally appended to each sort key, separated by :.
     * Sort directions can be 'asc' or 'desc' and defaults to 'asc'.
     * Examples: sort=[number:asc,title:desc], sort=[number, title:desc]
     * This function retrieves sorting keys from uri and returns an array.
    */
    private static function getSortKeys($request) {
        $sort_key_array = [];

        // Get querystring variables from url
        $params = $request->getQueryParams();

        if (array_key_exists('sort', $params)) {
            $sort = preg_replace('/^\[|\]$|\s+/', '', $params['sort']);  // remove white spaces, [, and ]
            $sort_keys = explode(',', $sort); //get all the key:direction pairs
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


//Get an author's books
    public static function getAuthorBooks(string $id) {
        $books = self::findOrFail($id)->books;
        return $books;
    }
    // Define the many-to-many relationship between Books and Author model classes.
    public function books(){
        return $this->belongsToMany(Book::class, 'author_and_book', 'book_id', 'author_id');
    }


    //search for authors
    public static function searchAuthors($term){
        if (is_numeric($term)) {
            $query = self::where('author_id', '=', $term);
        } else {
            $query = self::where('firstname', 'like', "%$term%")
                ->orWhere('lastname', 'like', "%$term%");
        }
        return $query->get();

    }

    //Insert a new author
    public static function createAuthors($request) {

        //Retrieve parameters from request body
        $params = $request->getParsedBody();
        //Create a new author instance
        $author = new Author();

        //Set the book's attributes
        foreach($params as $field => $value) {
            $author->$field = $value;
        }

        //Insert the student into the database
        $author->save();

        return $author;
    }

    //Insert a new publisher
    public static function createPublishers($request) {

        //Retrieve parameters from request body
        $params = $request->getParsedBody();
        //Create a new author instance
        $publisher = new Publisher();

        //Set the book's attributes
        foreach($params as $field => $value) {
            $publisher->$field = $value;
        }

        //Insert the student into the database
        $publisher->save();

        return $publisher;
    }

}