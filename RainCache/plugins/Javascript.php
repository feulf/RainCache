<?php

/**
 * Compress the CSS
 * @param type $html
 * @return type 
 */
function JavascriptRainCachePlugin($html, $pluginConfig, $container) {

    // get the context variables
    $config         = $container["config"];

    $base_dir       = $config['base_dir'];
    $cacheFolder    = $config['absolute_cache_dir']; // css cache folder
    
    // check if there's comments
    $htmlToCheck = preg_replace("<!--.*?-->", "", $html);

    // search for javascript
    preg_match_all("/<script.*src=\"(.*?\.js)\".*>/", $htmlToCheck, $matches);
    $externalUrl = array();
    $javascript = "";
    
    $javascriptFiles = $matches[1];
    $md5Name = "";
    foreach ($javascriptFiles as $file) {
        $md5Name .= basename($file);
    }

    // set the name
    $cachedFilename = md5($md5Name);
    $cachedFilepath = $config['absolute_cache_dir'] . $cachedFilename . ".js";
    $cachedFileUrl  = $config['cache_url'] . $cachedFilename . ".js";

    if (!file_exists($cachedFilepath)) {
        foreach ($matches[1] as $url) {

            // if a JS is repeat it takes only the first
            if (empty($urlArray[$url])) {
                $urlArray[$url] = $url;

                // if not external
                if( preg_match('#(http|https)://#', $url) ){
                    $javascriptFile = file_get_contents( $url );
                }
                else{
                    $javascriptFile = file_get_contents( $base_dir . $url );
                }

                // minify the js
                $javascriptFile = preg_replace("#/\*.*?\*/#", "", $javascriptFile);
                $javascriptFile = preg_replace("#\n+|\t+| +#", " ", $javascriptFile);

                $javascript .= "/*---\n Javascript compressed in Rain \n {$url} \n---*/\n\n" . $javascriptFile . "\n\n";
            }
        }

        if (!is_dir($cacheFolder))
            mkdir($cacheFolder, 0755, $recursive = true);

        // save the stylesheet
        file_put_contents($cachedFilepath, $javascript);
    }

    $html = preg_replace("/<script.*src=\"(.*?\.js)\".*>/", "", $html);
    $tag = '<script src="' . $cachedFileUrl . '"></script>';

    if ($config['javascript']['position'] == 'bottom') {
        $html = preg_replace("/<\/body>/", $tag . "</body>", $html);
    } else {
        $html = preg_replace("/<head>/", "<head>\n" . $tag, $html);
    }

    return $html;
}