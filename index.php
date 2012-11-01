<?php 

// include the cache
// this is the first step to make RainCache works
// and remember to set your configuration in RainCache/
require "RainCache/cache.php";

// load a random HTML 
// here there it should be your PHP program
// so heavy queries, operations and other fancy slow stuff
echo file_get_contents("test.html");