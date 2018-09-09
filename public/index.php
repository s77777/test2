<?php

if ($_SERVER['SERVER_NAME']=='test2.local')
{
    error_reporting(E_ALL);
    ini_set('display_errors', TRUE);
    ini_set('display_startup_errors', TRUE);
}
else
{
    error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING & ~E_PARSE);
    ini_set('display_errors', FALSE);
    ini_set('display_startup_errors', FALSE);
}

try
{
    $start = microtime(1);
    define('APP_PATH', realpath('..') . '/');
    define('APP_PATH_CONF', APP_PATH . 'app/Config/');
    define('APP_PATH_CLASSES', APP_PATH . 'app/Classes/');
    define('APP_PATH_VIEWS', APP_PATH . 'app/Views/');
    define('APP_PATH_UPLOAD', APP_PATH . 'app/Upload/');
    require APP_PATH_CONF . 'autoloader.php';
    $p = new Page();
    $p->getContent();
}
catch (\Exception $e)
{
    echo 'Error - '.$e->getMessage()."\n".'Line: '.$e->getLine()."\n".'File: '.$e->getFile();
}