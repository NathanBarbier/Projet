<?php
//import all models
require_once "../../traitements/header.php";

session_destroy();
header("location:".CONTROLLERS_URL."general/connexion.php");
?>