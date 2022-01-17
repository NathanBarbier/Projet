<?php
//import all models
require_once "../../services/header.php";

$action = GETPOST('action');

$idOrganization = $_SESSION["idOrganization"] ?? false;
$rights = $_SESSION["rights"] ?? false;

$success = false;
$errors = array();

$tpl = "listeProjets.php";

if($rights === "admin")
{
    $Organization = new Organization($idOrganization);

    require_once VIEWS_PATH."admin/".$tpl;
}
else
{
    header("location:".ROOT_URL."index.php");
}