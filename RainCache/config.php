<?php

/**
 * Configuration
 */
$RainCacheConfig = array(

    'cache_dir'         => 'cache/',     // cache directory
    'base_url'          => null,         // if null RainCache will guess your application base_url
    'cache'             => true,         // enable the cache, keep false only for debugging
    'reset_parameter'   => 'rain_cache', // set this parameter and RainCache will reset the cache
    'cache_expiration'  => 86400,        // seconds for cache expiration
    'prefix'            => 'RainCache.', // prefix of cache files

);