<?php


/**
 * Compress the CSS
 * @param type $html
 * @return type 
 */
function RainCompressCSS($html, $RainCompressConfig) {
    
    $base_dir = $RainCompressConfig['base_dir'];

    // search for all stylesheet
    if (!preg_match_all("/<link.*href=\"(.*?\.css)\".*>/", $html, $matches))
        return $html; // return the HTML if doesn't find any

    // prepare the variables
    $externalUrl = array();
    $css = $cssName = "";
    $urlArray = array();

    $cssFiles = $matches[1];
    $md5Name = "";
    foreach ($cssFiles as $file) {
        $md5Name .= basename($file);
    }

    $cachedFilename = md5($md5Name);
    $cacheFolder = $RainCompressConfig['absolute_cache_dir']; // css cache folder
    $cachedFilepath = $cacheFolder . $cachedFilename . ".css";
    $cachedFileUrl = $RainCompressConfig['cache_url'] . $cachedFilename . ".css";

    if (!file_exists($cachedFilepath)) {

        // read all the CSS found
        foreach ($cssFiles as $url) {

            // if a CSS is repeat is not added again
            if (empty($urlArray[$url])) {

                $urlArray[$url] = 1;

                // if it's external
                if( preg_match('#http://#', $url) )
                    $stylesheetFile = file_get_contents( $url );
                
                else
                    $stylesheetFile = file_get_contents( $base_dir . $url );
                

                // read file
                
                $base_script = str_repeat( "../", count(explode("/",$RainCompressConfig['cache_dir']))-1 );
                
                // optimize image URL
                if (preg_match_all("/url\({0,1}(.*?)\)/", $stylesheetFile, $matches)) {
                    foreach ($matches[1] as $imageUrl) {
                        $imageUrl = preg_replace("/'|\"/", "", $imageUrl);
                        $real_path = RainReducePath( $base_script . dirname($url) . "/" . $imageUrl);
                        $stylesheetFile = str_replace($imageUrl, $real_path, $stylesheetFile);
                    }
                }

                // remove the comments
                $stylesheetFile = preg_replace("!/\*[^*]*\*+([^/][^*]*\*+)*/!", "", $stylesheetFile);

                // minify the CSS
                $stylesheetFile = preg_replace("/\n|\r|\t|\s{4}/", "", $stylesheetFile);

                $css .= "/*---\n CSS compressed in Rain \n {$url} \n---*/\n\n" . $stylesheetFile . "\n";
            }
        }

        if (!is_dir($cacheFolder))
            mkdir($cacheFolder, 0755, $recursive = true);

        // save the stylesheet
        file_put_contents($cachedFilepath, $css);
    }

    // remove all the old stylesheet from the page
    $html = preg_replace("/<link.*href=\"(.*?\.css)\".*>/", "", $html);

    // create the tag for the stylesheet 
    $tag = '<link href="' . $cachedFileUrl. '" rel="stylesheet" type="text/css">';

    // add the tag to the end of the <head> tag
    $html = str_replace("</head>", $tag . "\n</head>", $html);

    // return the stylesheet
    return $html;
}