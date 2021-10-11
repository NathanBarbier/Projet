<?php

$uri = $_SERVER['REQUEST_URI'];
$protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
$url = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

$dir = __DIR__;
$explode = explode(DIRECTORY_SEPARATOR, $dir);
$rootDir = str_replace(DIRECTORY_SEPARATOR.$explode[max(array_keys($explode))], '', $dir);
$rootDir .= DIRECTORY_SEPARATOR;

$pageName = explode("/", $url)[max(array_keys(explode("/", $url)))];
$pageName = explode("?", $pageName)[min(array_keys(explode("?", $pageName)))];

$rootUrl = explode("Projet", $url)[0];
$rootUrl .= "Projet/";

// DIR PATH
define('ROOT_PATH', $rootDir);
define('MODELS_PATH', $rootDir.'modeles'.DIRECTORY_SEPARATOR);
define('IMG_PATH', $rootDir.'images'.DIRECTORY_SEPARATOR);
define('CONTROLLERS_PATH', $rootDir.'Controllers'.DIRECTORY_SEPARATOR);
define('VIEWS_PATH', $rootDir.'views'.DIRECTORY_SEPARATOR);
define('PROCESS_PATH', $rootDir.'traitements'.DIRECTORY_SEPARATOR);
define('PHP_MAILER_PATH', $rootDir.'vendor'.DIRECTORY_SEPARATOR.'phpmailer'.DIRECTORY_SEPARATOR.'phpmailer'.DIRECTORY_SEPARATOR.'src'.DIRECTORY_SEPARATOR);

// URL PATH
define('ROOT_URL', $rootUrl);
define('MODELS_URL', $rootUrl.'modeles/');
define('IMG_URL', $rootUrl.'images/');
define('CONTROLLERS_URL', $rootUrl.'Controllers/');
define('VIEWS_URL', $rootUrl.'views/');
define('PROCESS_URL', $rootUrl.'traitements/');
define('JS_URL', $rootUrl.'js/');
define('AJAX_URL', $rootUrl.'Ajax/');
define('PHP_MAILER_URL', $rootUrl.'vendor/phpmailer/phpmailer/src/');

?>