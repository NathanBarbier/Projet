<?php
require_once "services/header.php";

$rights = $_SESSION["rights"] ?? false;
$email = $_SESSION["email"] ?? false;

$connected = !empty($_SESSION) ? true : false;

if($rights === "admin")
{
    header("location:".CONTROLLERS_URL."admin/index.php");
    // header("location:".VIEWS_URL."admin/index.php");
    exit;
}

if($rights === "user")
{
    header("location:".VIEWS_URL."membre/index.php");
    exit;
}

if($rights === 'needConsent')
{
    header("location:".CONTROLLERS_URL."visiteur/needConsent.php");
    exit;
}

if(!$connected)
{
    // echo VIEWS_PATH."visiteur/connexion.php";
    header("Location:".CONTROLLERS_URL."visiteur/connexion.php");
    exit;
    // header("Location:views/visiteur/connexion.php");
}