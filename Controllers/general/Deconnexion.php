<?php
//import all models
require_once "../../services/header.php";

session_destroy();
header("location:".CONTROLLERS_URL."general/connexion.php");
?>