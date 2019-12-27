<?php

namespace App\Tests;

class BrowserKitUtil
{

    public function getRelativeUri($uri)
    {   
        return preg_replace("#^http://(.*?)/#", "/", $uri);
    }
    
}
