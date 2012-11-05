<?php

namespace Rain;

use Rain;

// example.com/tests/Simplex/Tests/FrameworkTest.php
class CacheTest extends \PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        require __DIR__ . "/../RainCache/config.php";
        $this->config = $RainCacheConfig;
    }


    /**
     * Config file is there and is an array
     */
    public function testCache()
    {
        // check the cache
    }
}
