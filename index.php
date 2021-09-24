<?php
require_once "traitements/header.php";

$rights = $_SESSION["rights"] ?? false;
$email = $_SESSION["email"] ?? false;

$connected = !empty($_SESSION) ? true : false;

if($rights === "admin")
{
    header("location:".VIEWS_URL."admin/index.php");
}

if($rights === "user")
{
    header("location:".VIEWS_URL."membres/index.php");
}

if(!$connected)
{
    // echo VIEWS_PATH."general/connexion.php";
    header("Location:".CONTROLLERS_URL."general/connexion.php");
    // header("Location:views/general/connexion.php");
}