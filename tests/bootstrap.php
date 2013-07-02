<?php

function prs0_autoload($className)
{
    $className = ltrim($className, '\\');
    $fileName  = '';
    $namespace = '';
    if ($lastNsPos = strrpos($className, '\\')) {
        $namespace = substr($className, 0, $lastNsPos);
        $className = substr($className, $lastNsPos + 1);
        $fileName  = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
    }
    $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';
    require_once $fileName;
}

spl_autoload_register('prs0_autoload');

define('TEST_PATH', realpath(dirname(__FILE__)));
define('SOURCE_PATH', realpath(TEST_PATH . '/../src'));

set_include_path(get_include_path() . PATH_SEPARATOR . SOURCE_PATH);
