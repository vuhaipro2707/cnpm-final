<?php 
    // class auto require
    spl_autoload_register(function ($class) {
        $paths = ['core/', 'app/controllers/', 'app/models/'];
    
        foreach ($paths as $path) {
            $file = $path . $class . '.php';
            if (file_exists($file)) {
                require_once $file;
                return;
            }
        }
    });
    

?>