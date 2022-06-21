<?php
/**
 * Author: Anant Batgali
 * Date: 5/24/22
 * File: Publisher.php
 * Description:
 */


/**
 * Author: Anant Batgali
 * Date: 5/24/22
 * File: Publisher.php
 * Description: Model for Publisher resource
 */

namespace BooksAPI\Models;

use Illuminate\Database\Eloquent\Model;

class Publisher extends Model
{
    //the table associated with this model
    protected $table = 'publisher';

    //the primary key of the table
    protected $primaryKey = 'publisher_id';

    //the PK is auto-incremented
    public $incrementing = false;

    //if te updated_at and created_at columns aren't used
    public $timestamps = false;

    //retrieve all publishers
    public static function getPublishers($request)
    {

        /*********** code for pagination and sorting *************************/
        //get the total number of row count
        $count = self::count();

        //Get querystring variables from url
        $params = $request->getQueryParams();

        //do limit and offset exist?
        $limit = array_key_exists('limit', $params) ? (int)$params['limit'] : 50;   //items per page
        $offset = array_key_exists('offset', $params) ? (int)$params['offset'] : 0;  //offset of the first item

        //pagination
        $links = self::getLinks($request, $limit, $offset);

        //build the query to get all related data
        $query = self::with('books');
        $query = $query->skip($offset)->take($limit);  //limit the rows

        //code for sorting
        $sort_key_array = self::getSortKeys($request);

        //soft the output by one or more columns
        foreach ($sort_key_array as $column => $direction) {
            $query->orderBy($column, $direction);
        }

        //retrieve the courses
        $publishers = $query->get();  //Finally, run the query and get the results

        //construct the data for response
        $results = [
            'totalCount' => $count,
            'limit' => $limit,
            'offset' => $offset,
            'links' => $links,
            'sort' => $sort_key_array,
            'data' => $publishers
        ];

        return $results;
    }

    // retrieve a publisher as per the id
    public static function getPublisherById(string $id)
    {
        $publisher = self::findOrFail($id);
        return $publisher;
    }

    //Insert a new publisher
    public static function createPublishers($request) {

        //Retrieve parameters from request body
        $params = $request->getParsedBody();
        //Create a new Publisher instance
        $publisher = new Publisher();

        //Set the publisher's attributes
        foreach($params as $field => $value) {
            $publisher->$field = $value;
        }

        //Insert the publisher into the database
        $publisher->save();

        return $publisher;
    }

    // 1-M relationship b/w publishers and books
    public function books()
    {
        return $this->hasMany(Book::class, 'publisher_id');
    }

    //Update a publisher
    public static function updatePublisher($request) {
        //Retrieve parameters from request body
        $params = $request->getParsedBody();
        //Retrieve id from the request url
        $id = $request->getAttribute('id');
        $publisher = self::findOrFail($id);
        if(!$publisher) {
            return false;
        }
        //update attributes of the publisher
        foreach($params as $field => $value) {
            $publisher->$field = $value;
        }
        //save the publisher into the database
        $publisher->save();
        return $publisher;
    }
    //Delete a Publisher
    public static function deletePublisher($request) {
        //Retrieve id from the request
        $id = $request->getAttribute('id');
        $publisher = self::findOrFail($id);
        return($publisher ? $publisher->delete() : $publisher);
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
     * Example: sort=[publisher_name:asc,website:desc]
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

    public static function searchPublishers($term)
    {
        if (is_numeric($term)) {
            $query = self::where('publisher_id', '=', $term);
        } else {
            $query = self::where('publisher_name', 'like', "%$term%")
                ->orWhere('website', 'like', "%$term%")
                ->orWhere('address', 'like', "%$term%");
        }

        $matchedPublishers = $query->get();

        //construct the data for response
        $results = [
            'data' => $matchedPublishers
        ];

        return $results;
    }
}