<?php

/**
 * @param $class
 */
function pluginAutoloader($class)
{
    $class = ltrim($class, '\\');

    $class = str_replace(__NAMESPACE__, '', $class);

    $path = plugin_dir_path(__FILE__) .
        str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';

    if (file_exists($path)) {
        require_once $path;
    }
}

spl_autoload_register('pluginAutoloader');