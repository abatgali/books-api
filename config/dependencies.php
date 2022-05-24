<?php
/**
 * Author: "Emma Parker"
 * Date: 5/22/22
 * File: dependencies.php
 *Description: define all the dependencies. Passed to routing functions as the callback routines
 */
use DI\Container;
//add all use statements here
use BooksAPI\Controllers\{
    BookController,
    AuthorController
};

return function(Container $container) {
    // define all dependency functions here
        //set a dependency called "Book"
        $container->set('Book', function(){
            return new BookController();
        });
        $container->set('Author', function (){
            return new AuthorController();
        });
<<<<<<< Updated upstream
=======
        //dependencies for genres table
        $container->set('Genre', function () {
            return new GenreController();
        });
        //dependencies for Author and Books table
        $container->set('AuthorAndBook', function (){
            return new AuthorAndBookController();
        });
>>>>>>> Stashed changes
};