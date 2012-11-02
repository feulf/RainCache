# RainCache 

RainCache is an automatic, easy and fast cache library for PHP.
It uses register_shutdown_function() to catch the output of your script and cache it.


##How to use it?
Copy the RainCache folder in your project and add this code in your main script:
```
require "RainCache/cache.php";
```


##Configuration
You can change the configuration in:
```
RainCache/config.php
```

cache: true if you want to save/load the cache (set false only for debugging)
cache_dir: the cache directory
cache_expiration: the expiration time for the cache
prefix: prefix for the cache filename


##Plugins
RainCache can load plugins. You can find already 3 experimental plugins:
HTML, compress the HTML
CSS, compress all stylesheet files in a single file
Javascript, compress all javascript in a single file (can break the page)
```

##Licence
```
RainCache is under MIT license
```


##TODO:
- resize image plugin
- domain sharding
- esi include to have dynamic blocks
- improve the CSS and JS compression