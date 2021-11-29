<?php
//import all models
require_once "../../services/header.php";

$action = GETPOST('action');

$idOrganization = $_SESSION["idOrganization"] ?? false;
$rights = $_SESSION["rights"] ?? false;

$organization = new Organization($idOrganization);

$success = false;
$errors = array();

$tpl = "listeProjets.php";

if($rights === "admin")
{
    $Project = new Project();

    $Project->setidOrganization($idOrganization);

    $currentProjects = $Project->fetchAll();

    require_once VIEWS_PATH."admin/".$tpl;
}
else
{
    header("location:".ROOT_URL."index.php");
}