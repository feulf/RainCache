<?php


    /**
     *  RainCache
     *  -------------
     *	RainCache is an automatic, easy and fast cache.
     *  It uses register_shutdown_function to catch the output of your website, compress it and cache it,
     *  so the next time your page will load faster
     *  
     *  @version Alpha 1
     *	Distributed under MIT license http://www.opensource.org/licenses/mit-license.php
     */


    #--------------------------------
    # Start the output buffer
    #--------------------------------
    ob_start();


    #--------------------------------
    # Load the configuration
    #--------------------------------
    require __DIR__ . "/config.php";



    #--------------------------------
    # Load the functions
    #--------------------------------
    require __DIR__ . "/library/functions.php";



    #--------------------------------
    # Prepare plugins
    #--------------------------------
    $plugins = array(
                        "HTML"         => array(),
                        //"CSS"        => array(),
                        //"Javascript" => array()
                    );


    #--------------------------------
    # Prepare config
    #--------------------------------
    $RainCacheConfig["plugins"] = $plugins;



    #--------------------------------
    # Load the cache
    #--------------------------------
    rainCacheLoad($RainCacheConfig);
