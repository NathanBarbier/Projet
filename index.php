<?php
require_once "services/header.php";

$rights = $_SESSION["rights"] ?? false;
$email = $_SESSION["email"] ?? false;

if($rights === "admin")
{
    header("location:".CONTROLLERS_URL."admin/index.php");
    exit;
}

if($rights === "user")
{
    header("location:".VIEWS_URL."member/index.php");
    exit;
}

if($rights === 'needConsent')
{
    header("location:".CONTROLLERS_URL."visitor/needConsent.php");
    exit;
}

if(empty($rights))
{
    session_destroy();
    header("Location:".CONTROLLERS_URL."visitor/login.php");
    exit;
}