<?php


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