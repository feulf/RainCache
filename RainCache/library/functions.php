<?php

/**
 * Load the cache if there is any
 * @global string $config
 * 
 */
function rainCacheLoad($config)
{

    // base directory
    $config['base_dir'] = getcwd() . "/";

    // absolute cache directory
    $config['absolute_cache_dir'] = $config['base_dir'] . $config['cache_dir'];

    // if cache is enabled
    if ($config['cache']) {

        // set the cache filepath
        $config['cache_filepath'] = $config['absolute_cache_dir'] . $config['prefix'] .
                        md5($_SERVER['REQUEST_URI'] . serialize($container)) . '.html';

        // if the cache is valid draw the cache and exit
        if (file_exists($config['cache_filepath'])
            && (time() - filemtime($config['cache_filepath']) < $config['cache_expiration'] )) {
            header("via: RainCache", $replace = true);
            echo file_get_contents($config['cache_filepath']);
            exit;
        }
    }
    if (!$config['base_url']) {
        $base_dir = substr(dirname($_SERVER['SCRIPT_FILENAME']), strlen($_SERVER['DOCUMENT_ROOT']));
        $config['base_url'] = "http://" . $_SERVER['HTTP_HOST'] . $base_dir . "/";
    }

    // create the cache URL
    $config['cache_url'] = $config['base_url'] . $config['cache_dir'];

    // reassign config to the container
    $container["config"] = $config;

    // register the shutdown function to save the cache when PHP ends
    register_shutdown_function('RainCacheSave', $config);
}

/**
 * Compress HTML, CSS and Javascript
 * @global type $config
 * 
 */
function rainCacheSave($config)
{

    // get the Output
    $html = ob_get_clean();

    // if there is an error don't save it on cache
    $error = error_get_last();
    if ($error['type'] === E_ERROR) {
        die;
    }

    // load plugins
    if (isset($config["plugins"])) {
        $html = loadPlugins($html, $config);
    }

    // if cache is enabled
    if ($config['cache']) {

        // create the directory
        if (!is_dir($config['absolute_cache_dir'])) {
            mkdir($config['absolute_cache_dir'], 0777, $recursive = true);
        }

        // save the html in cache
        file_put_contents($config['cache_filepath'], $html);
    }


    // draw the Output
    echo $html;
}

/**
 * 
 * @assert (123, null) == 123
 * 
 * Load the plugins
 * @param type $html
 * @param type $config
 * @return type
 */
function loadPlugins($html, $config)
{

    $plugins = $config["plugins"];
    if ($plugins) {
        // run the plugins
        foreach ($plugins as $pluginName => $pluginConfig) {

            // require the plugin
            require __DIR__ . "/../Plugins/" . $pluginName . ".php";

            // set the plugin function name
            $pluginFunction = $pluginName . "RainCachePlugin";

            // execute the function
            $html = $pluginFunction($html, $pluginConfig, $config);
        }
    }
    return $html;
}
