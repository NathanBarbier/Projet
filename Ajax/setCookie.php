<?php 
// import all models
require_once "../services/header.php";
// only allow access to ajax request
if( isset( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && ( $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' ) )
{
    $action = GETPOST('action');

    switch($action) {
        case 'consentCookie':
            setcookie("consentCookie", 1, [
                'expires' => time()+86400,
                 'samesite' => 'None',
                 'secure' => false, //todo true when the website support https
                 'httpOnly' => true
             ]);
        case 'checkCookieConsent':
            if(isset($_COOKIE['consentCookie']) && $_COOKIE['consentCookie'] == 1)
            {
                echo json_encode(true);
            }
            else
            {
                echo json_encode(false);
            }
    }
}
else
{
    header("location:".ROOT_URL."index.php");
}
?>