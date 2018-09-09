<?php

spl_autoload_register(function($classes){
        $filename = $classes . '.php';
        $file = APP_PATH_CLASSES . $filename;
        if (false == file_exists($file)) {
                echo 'Class "'.$classes. '" not found this server';
                exit;
           }
        require ($file);
});