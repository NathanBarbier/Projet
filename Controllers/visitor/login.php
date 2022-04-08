<?php
//import all models
require_once "../../services/header.php";

$envoi = GETPOST('envoi');

$email  = GETPOST('email');
$password = GETPOST('password');
$message = GETPOST('message');
$rememberMe = GETPOST('rememberMe');

$User = new User();
$Organization = new Organization();

$errors = array();
$success = false;

$tpl = "login.php";
$page = CONTROLLERS_URL."visitor/".$tpl;

if(isset($_COOKIE["remember_me"]))
{
    $cookie = explode("-", $_COOKIE["remember_me"]);

    if($User->checkToken($cookie[0], $cookie[1]))
    {
        // get the user ip adress
        $userIp = $_SERVER['REMOTE_ADDR'];

        // check the ip adress is banned
        $BannedIp = new BannedIp($userIp);

        if($BannedIp->getRowid() == null) 
        {
            // attribute user properties
            $User->fetch($cookie[0]);

            // if user is admin then check if his ip address is allowed
            if($User->isAdmin())
            {
                $AllowedIp = new AllowedIp($userIp);

                // If the $AllowedIp has been fetched
                if(!empty($AllowedIp->getFk_user()) && $User->getRowid() == $AllowedIp->getFk_user())
                {
                    $_SESSION["rights"] = 'admin';
                    $_SESSION["idUser"] = intval($User->getRowid());
                    $_SESSION["idOrganization"] = $idOrganization = intval($User->getFk_organization());
        
                    LogHistory::create($User->getRowid(), 'connect', 'user', $User->getRowid(), null, null, $User->getFk_organization(), "INFO", null, $ip, $page);
                    $success = true;
                    header('location:'.ROOT_URL.'index.php');
                    exit;
                }
                else
                {
                    $errors[] = "Votre adresse ip n'est pas autorisée à accéder à l'interface administrateur.";
                }
            }
            else
            {
                $_SESSION["rights"] = 'user';
                $_SESSION["idUser"] = intval($User->getRowid());
                $_SESSION["idOrganization"] = $idOrganization = intval($User->getFk_organization());
    
                LogHistory::create($User->getRowid(), 'connect', 'user', $User->getRowid(), null, null, $User->getFk_organization(), "INFO", null, $ip, $page);
                $success = true;
                header('location:'.ROOT_URL.'index.php');
                exit;
            }


        }
        else
        {
            $errors[] = 'Cette adresse ip est bannie.';
        }
    }
}

require_once VIEWS_PATH."visitor/".$tpl;