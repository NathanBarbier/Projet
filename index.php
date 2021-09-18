<?php
require_once "traitements/header.php";

$data = !empty($_GET["data"]) ? json_decode($data) : null;

$rights = $_SESSION["habilitation"] ?? false;
$email = $_SESSION["email"] ?? false;

$connected = !empty($_SESSION) ? true : false;

// if($email)
// {
//     session_destroy();
//     header("location:".VIEWS_URL."general/connexion.php");
// }

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
    header("Location:".VIEWS_URL."general/connexion.php");
    // header("Location:views/general/connexion.php");
}