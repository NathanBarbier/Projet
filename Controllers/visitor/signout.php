<?php
//import all models
require_once "../../services/header.php";
// get the user ip adress
$ip = $_SERVER['REMOTE_ADDR'];

setcookie(
    'remember_me',
    "",
    time() - 604800,
    '',
    '',
    false, //true on production otherwise false
    true
);
$idUser = $_SESSION['idUser'] ?? false;
$idOrganization = $_SESSION['idOrganization'] ?? false;
if($idUser && $idOrganization) {
    $User = new User($idUser);
    LogHistory::create($idOrganization, $idUser, "INFO", 'disconnect', 'user',$User->getLastname().' '.$User->getFirstname(), null, null, null, $ip);
}

session_destroy();
header("location:".CONTROLLERS_URL."visitor/login.php");
?>