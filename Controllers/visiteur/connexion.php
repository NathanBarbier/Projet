<?php
//import all models
require_once "../../services/header.php";

$envoi = GETPOST('envoi');

$email = GETPOST('email');
$password = GETPOST('password');
$message = GETPOST('message');
$rememberMe = GETPOST('rememberMe');

$User = new User();
$Organization = new Organization();

$errors = array();
$success = false;

$data = array();

$tpl = "connexion.php";

if(isset($_COOKIE["remember_me"]))
{
    $cookie = explode("-", $_COOKIE["remember_me"]);

    if($User->checkToken($cookie[0], $cookie[1]))
    {
        $User->fetch($cookie[0]);

        $_SESSION["rights"] = $User->isAdmin() == 1 ? "admin" : "user";
        $_SESSION["idUser"] = intval($User->getRowid());
        $_SESSION["idOrganization"] = $idOrganization = intval($User->getFk_organization());

        LogHistory::create($idOrganization, $User->getRowid(), 'INFO', 'connect', 'user', $User->getLastname().' '.$User->getFirstname());
        $success = true;
        header('location:'.ROOT_URL.'index.php');
        exit;
    }
}

require_once VIEWS_PATH."visiteur/".$tpl;