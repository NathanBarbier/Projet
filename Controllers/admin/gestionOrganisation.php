<?php
//import all models
require_once "../../traitements/header.php";

$action = GETPOST('action');

$idOrganization = $_SESSION["idOrganization"] ?? false;
$rights = $_SESSION["rights"] ?? false;

$Organization = new Organization($idOrganization);

$tpl = "gestionOrganisation.php";
$data = new stdClass;

if($rights === "admin")
{
    
    if($action == "deleteOrganization")
    {
        $Organization->delete();
        header("location:".ROOT_URL."index.php");
    }

    
    $CurrentOrganization = new stdClass;

    $CurrentOrganization->name = $Organization->getName();
    $CurrentOrganization->email = $Organization->getEmail();
    $CurrentOrganization->membersCount = $Organization->countUsers();
    $CurrentOrganization->teamsCount = $Organization->countTeams();

    require_once VIEWS_PATH."admin".DIRECTORY_SEPARATOR.$tpl;
}
else
{
    header("location:".ROOT_URL."index.php");
}


?>