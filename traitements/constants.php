<?php

/**
 * Racine du projet
 */

$uri = $_SERVER['REQUEST_URI'];
$protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
$url = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

$dir = __DIR__;
$explode = explode(DIRECTORY_SEPARATOR, $dir);
$rootDir = str_replace(DIRECTORY_SEPARATOR.$explode[max(array_keys($explode))], '', $dir);

$rootUrl = $url;

define('ROOT_PATH', $rootDir);
define('CONTROLLERS_PATH', $rootDir.DIRECTORY_SEPARATOR.'Controllers'.DIRECTORY_SEPARATOR);
define('VIEWS_PATH', $rootUrl.'views'.DIRECTORY_SEPARATOR);
define('MODELS_PATH', $rootDir.DIRECTORY_SEPARATOR.'modeles'.DIRECTORY_SEPARATOR);
define('PROCESS_PATH', $rootDir.DIRECTORY_SEPARATOR.'traitements'.DIRECTORY_SEPARATOR);
define('IMG_PATH', $rootDir.DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR);
 
?>