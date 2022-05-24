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
    public $incrementing = true;

    //if te updated_at and created_at columns aren't used
    public $timestamps = false;

    //retrieve all publishers
    public static function getPublishers()
    {
        $publishers = self::all();
        return $publishers;
    }

    // retrieve a publisher as per the id
    public static function getPublisherById(string $id)
    {
        $publisher = self::findOrFail($id);
        return $publisher;
    }
}