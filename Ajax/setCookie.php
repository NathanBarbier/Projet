<?php 
// import all models
require_once "../services/header.php";

    $action = GETPOST('action');

    switch($action) {
        case 'consentCookie':
            setcookie("consentCookie", 1, [
                'expires' => time()+86400,
                 'samesite' => 'None',
                 'secure' => true,
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
?>