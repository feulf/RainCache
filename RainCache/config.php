<?php

/**
 * Configuration
 */
$RainCacheConfig = array(

    'cache_dir'         => 'cache/compress/',
    'base_url'          => null,         // if null RainCache will guess your application base_url
    'cache'             => true,         // enable the cache, keep false only for debugging
    'reset_parameter'   => 'rain_cache', // set this parameter and RainCache will reset the cache
    'cache_expiration'  => 86400,        // seconds for cache expiration
    'prefix'            => 'RainCache.', // prefix of cache files
    
    // configuration for HTML
    'html'              => array(
                                   'status' => true // set true to enable HTML compression
                                ),
    
    // configuration for CSS
    'css'               => array(
                                    'status' => true // set true to enable CSS compression
                                ), 
    
    // configuration for Javascript
    'javascript'        => array(
                                    'status' => false, // set true to enable the Javascript compression, this configuration can break the site
                                    'position' => 'bottom' // top/bottom/preserve TODO: Preserve should be the default option
                                ),
);