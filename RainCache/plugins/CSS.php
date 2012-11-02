<?php


/**
 * Compress the CSS
 * @param type $html
 * @return type 
 */
function CSSRainCachePlugin($html, $pluginConfig, $container) {

    $baseDir        = $config['base_dir']; // base directory
    $baseUrl        = $config['base_url']; // base url
    $cacheFolder    = $config['absolute_cache_dir']; // css cache folder
    $cacheDir       = $config['cache_dir']; // cache directory
    $cacheUrl       = $config['cache_url']; // cache Url
    $cache          = $config['cache']; // cache enabled


    // search for all stylesheet
    if (!preg_match_all("/<link.*href=\"(.*?\.css)\".*>/", $html, $matches))
        return $html; // return the HTML if doesn't find any

    // list of css files
    $cssFiles = $matches[1];
    
    // content compressed of the css files
    $compressCssString = "";
    
    // array of URLs
    $urlArray = array();

    // set the name of the cachedFilepath
    $md5Name = "";
    foreach ($cssFiles as $file) 
        $md5Name .= basename($file);
    $cachedFilename = md5($md5Name);
    
    // set the filepath of the cached file
    $cachedFilepath = $cacheFolder . $cachedFilename . ".css";
    
    // set the url of the cached file
    $cachedFileUrl = $cacheUrl . $cachedFilename . ".css";

    // if cache is disabled or if the file doesn't exists
    if ( !$cache or !file_exists($cachedFilepath) ) {

        // read all the CSS found
        foreach ($cssFiles as $url) {
            
            // if a CSS is repeat is not added again
            if (empty($urlArray[$url])) {

                // save the url of this file
                $urlArray[$url] = 1;

                // if it's external
                if( preg_match('#http://#', $url) )
                    $stylesheetFile = file_get_contents( $url );
                
                else
                    $stylesheetFile = file_get_contents( $baseDir . $url );
                

                // read file
                
                $base_script = str_repeat( "../", count(explode("/",$cacheDir))-1 );

                // change all images and fonts URL with the right one
                if (preg_match_all("#url\((?:'|\")(.*?)(?:'|\")\)#", $stylesheetFile, $matches)) {
                    foreach ($matches[1] as $imageUrl) {
                        // if the url is absolute do not replace it
                        if( !preg_match('#http://|https://#', $imageUrl ) ){

                            $url = str_replace($baseUrl, "", $url);
                            $url = dirname($url);

                            $realPath = $base_script . $url . "/" . $imageUrl;
                            $reducedRealPath = RainReducePath( $realPath );
                            $stylesheetFile = str_replace($imageUrl, $reducedRealPath, $stylesheetFile);
                        }
                    }
                }

                // remove the comments
                $stylesheetFile = preg_replace("!/\*[^*]*\*+([^/][^*]*\*+)*/!", "", $stylesheetFile);

                // minify the CSS
                $stylesheetFile = preg_replace("/\n|\r|\t|\s{4}/", "", $stylesheetFile);

                $compressCssString .= "/*---\n CSS compressed in Rain \n {$url} \n---*/\n\n" . $stylesheetFile . "\n";
            }
        }

        if (!is_dir($cacheFolder))
            mkdir($cacheFolder, 0755, $recursive = true);

        // save the stylesheet
        file_put_contents($cachedFilepath, $compressCssString );
    }

    // remove all the old stylesheet from the page
    $html = preg_replace("/<link.*href=\"(.*?\.css)\".*>/", "", $html);

    // create the tag for the stylesheet 
    $tag = '<link href="' . $cachedFileUrl. '" rel="stylesheet" type="text/css">';

    // add the tag to the end of the <head> tag
    $html = str_replace("</head>", $tag . "\n</head>", $html);

    // return the html
    return $html;
}