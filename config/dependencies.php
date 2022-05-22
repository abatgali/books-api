<?php
/**
 * Author: "Emma Parker"
 * Date: 5/22/22
 * File: dependencies.php
 *Description: define all the dependencies. Passed to routing functions as the callback routines
 */
use DI\Container;
//add all use statements here
use BooksAPI\Controllers\BookController;

return function(Container $container) {
    // define all dependency functions here
        //set a dependency called "Book"
        $container->set('Book', function(){
            return new BookController();
        });
};