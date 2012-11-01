<?php

    /**
     * Load the cache if there is any
     * @global string $RainCacheConfig
     */
    function RainCacheLoad($RainCacheConfig) {

        // cache directory
        $RainCacheConfig['base_dir'] = getcwd() . "/";
        $RainCacheConfig['absolute_cache_dir'] = $RainCacheConfig['base_dir'] . $RainCacheConfig['cache_dir'];

        // if cache is enabled
        if ($RainCacheConfig['cache']) {

            // set the cache filepath
            $RainCacheConfig['cache_filepath'] = $RainCacheConfig['absolute_cache_dir'] . md5($_SERVER['REQUEST_URI']) . '.html';

            // if the cache is valid draw the cache and exit
            if (file_exists($RainCacheConfig['cache_filepath']) && (time() - filemtime($RainCacheConfig['cache_filepath']) < $RainCacheConfig['cache_expiration'] )) {
                echo file_get_contents($RainCacheConfig['cache_filepath']);
                exit;
            }

        }
        if( !$RainCacheConfig['base_url'] ){
            $base_dir = substr( dirname($_SERVER['SCRIPT_FILENAME']), strlen($_SERVER['DOCUMENT_ROOT']) );
            $RainCacheConfig['base_url'] = "http://" . $_SERVER['HTTP_HOST'] . $base_dir . "/";
        }

        // create the cache URL
        $RainCacheConfig['cache_url'] = $RainCacheConfig['base_url'] . $RainCacheConfig['cache_dir'];

        // register the shutdown function to save the cache when PHP ends
        register_shutdown_function('RainCacheSave', $RainCacheConfig);
    }




    /**
     * Compress HTML, CSS and Javascript
     * @global type $RainCacheConfig
     */
    function RainCacheSave($RainCacheConfig) {

        // if there is an error don't save it on cache
        $error = error_get_last();
        if ($error['type'] === E_ERROR) {
            die;
        }

        // get the Output
        $html = ob_get_clean();

        // compress CSS
        if ($RainCacheConfig['css']['status']){
            require __DIR__ . "/Compress/CSS.php";
            $html = RainCompressCSS($html, $RainCacheConfig);
        }

        // compress JS
        if ($RainCacheConfig['javascript']['status']){
            require __DIR__ . "/Compress/Javascript.php";
            $html = RainCompressJavascript($html, $RainCacheConfig);
        }

        // compress HTML
        if ($RainCacheConfig['html']['status']){
            require __DIR__ . "/Compress/HTML.php";
            $html = RainCompressHTML($html, $RainCacheConfig);
        }

        // draw the Output
        echo $html;
    }

    /**
     * Reduce the path with ../ and ./
     * @param type $path
     * @return type
     */
    function RainReducePath($path) {
        // reduce the path
        $path = str_replace("://", "@not_replace@", $path);
        $path = preg_replace("#(/+)#", "/", $path);
        $path = preg_replace("#(/\./+)#", "/", $path);
        $path = str_replace("@not_replace@", "://", $path);

        while (preg_match('#\w+/\.\./#', $path) ) {
            $path = preg_replace('#\w+/\.\./#', '', $path);
        }
        return $path;
    }