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

$rootUrl = "";

$splittedUrl = explode("/", $url);
foreach($splittedUrl as $urlPiece)
{
    $rootUrl .= $urlPiece . "/";
    if(strpos($urlPiece, "storieshelper") !== false)
    {
        break;
    }
}

$url = explode("?", $url)[0];

// DIR PATH
define('ROOT_PATH', $rootDir);
define('MODELS_PATH', $rootDir.'models'.DIRECTORY_SEPARATOR);
define('IMG_PATH', $rootDir.'images'.DIRECTORY_SEPARATOR);
define('CONTROLLERS_PATH', $rootDir.'controllers'.DIRECTORY_SEPARATOR);
define('SERVICES_PATH', $rootDir.'services'.DIRECTORY_SEPARATOR);
define('VIEWS_PATH', $rootDir.'views'.DIRECTORY_SEPARATOR);
define('ASSETS_PATH', $rootDir.'assets'.DIRECTORY_SEPARATOR);
define('REPO_PATH', $rootDir.'repositories'.DIRECTORY_SEPARATOR);
define('PHP_MAILER_PATH', $rootDir.'vendor'.DIRECTORY_SEPARATOR.'phpmailer'.DIRECTORY_SEPARATOR.'phpmailer'.DIRECTORY_SEPARATOR.'src'.DIRECTORY_SEPARATOR);

// URL PATH
define('ROOT_URL', $rootUrl);
define('MODELS_URL', $rootUrl.'models/');
define('IMG_URL', $rootUrl.'/assets/images/');
define('CONTROLLERS_URL', $rootUrl.'controllers/');
define('VIEWS_URL', $rootUrl.'views/');
define('SERVICES_URL', $rootUrl.'services/');
define('JS_URL', $rootUrl.'js/');
define('AJAX_URL', $rootUrl.'ajax/');
define('ASSETS_URL', $rootUrl.'assets/');
define('PHP_MAILER_URL', $rootUrl.'vendor/phpmailer/phpmailer/src/');
define('REPO_URL', $rootUrl.'repositories'.DIRECTORY_SEPARATOR);
?>