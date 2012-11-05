<?php

namespace Rain;

use Rain;

// example.com/tests/Simplex/Tests/FrameworkTest.php
class ConfigTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Config file is there and is an array
     */
    public function testConfig()
    {
        require __DIR__ . "/../RainCache/config.php";
        $this->assertInternalType('array', $RainCacheConfig);
    }
}
