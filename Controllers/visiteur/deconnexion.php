<?php
//import all models
require_once "../../services/header.php";
setcookie(
    'remember_me',
    "",
    time() - 604800,
);
$idUser = $_SESSION['idUser'] ?? false;
if($idUser) {
    $User = new User($idUser);
    LogHistory::create($idUser, 'disconnect', 'user',$User->getLastname().' '.$User->getFirstname());
}
session_destroy();
header("location:".CONTROLLERS_URL."visiteur/connexion.php");
?>