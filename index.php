<?php
require_once "traitements/header.php";

$rights = $_SESSION["habilitation"] ?? false;
$email = $_SESSION["email"] ?? false;

$connected = !isset($_SESSION) OR empty($_SESSION) ? false : true;

if($email)
{
    session_destroy();
    header("location:".VIEWS_PATH."general/connexion.php");
}

if($rights === "admin")
{
    header("location:".VIEWS_PATH."admin/index.php");
}

if($rights === "user")
{
    header("location:".VIEWS_PATH."membres/index.php");
}

if(!$connected)
{
    // echo VIEWS_PATH."general/connexion.php";
    header("Location:".VIEWS_PATH."general/connexion.php");
    // header("Location:views/general/connexion.php");
}