<?php

namespace App\Tests;

/**
 * Browser kit utility for unit and functional tests
 */
class BrowserKitUtil
{

    /**
     * Get the relative uri from an absolute or relative uri
     *
     * @param string $uri
     * @return void
     */
    public function getRelativeUri($uri)
    {   
        return preg_replace("#^http://(.*?)/#", "/", $uri);
    }
    
}
