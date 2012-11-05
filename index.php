<?php

// include the cache
// this is the first step to make RainCache works
// and remember to set your configuration in RainCache/
require "RainCache/cache.php";

// load a random HTML
// here there it should be your PHP program
// so heavy queries, operations and other fancy slow stuff
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Test HTML</title>
        <link href="template/style.css" rel="stylesheet" type="text/css"/>
    </head>
    <body>
        <img src="template/logo.png">
        <h1>RainCache</h1>
        <?php

            // query and slow operation here
            sleep(1);

        ?>
        <div>
            <p><b>RainCache</b> is an automatic, easy and fast cache for your PHP website.</p>
            <p>All you have to do is just include the main file in your main script.</p>
            <p>It uses a simple <b>plugins system</b>, so you can add plugin to compress HTML, CSS, Javascript, 
                resize images, domain sharding and so on, is all about your imagination.</p>
        </div>
        
        <div class="copy">
            <a href="https://github.com/rainphp/RainCache">by Federico Ulfo</a>
        </div>
    </body>
</html>