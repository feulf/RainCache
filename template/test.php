<!DOCTYPE html>
<html>
    <head>Test HTML</head>
    <link href="template/style.css" rel="stylesheet" type="text/css"/>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
    <body>
        <img src="template/logo.png">
        <?php
        
            // query and slow operation here
            sleep(3);
            echo "Your website with all the slow operations";
        
        ?>
    </body>
</html>