<?php
//import all models
require_once "../../services/header.php";
setcookie(
    'remember_me',
    "",
    time() - 604800,
);
session_destroy();
header("location:".CONTROLLERS_URL."visiteur/connexion.php");
?>