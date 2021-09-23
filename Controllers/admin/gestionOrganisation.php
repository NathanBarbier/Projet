<?php
//import all models
require_once "../../traitements/header.php";

$action = GETPOST('action');

$idOrganisation = $_SESSION["idOrganisation"] ?? false;
$rights = $_SESSION["habilitation"] ?? false;

$Organisation = new Organisation($idOrganisation);

$tpl = "gestionOrganisation.php";
$data = new stdClass;

if($rights === "admin")
{
    
    if($action == "deleteOrganisation")
    {
        $Organisation->delete();
        header("location:".ROOT_URL."index.php");
    }

    
    $CurrentOrganisation = new stdClass;

    $CurrentOrganisation->nom = $Organisation->getNom();
    $CurrentOrganisation->email = $Organisation->getEmail();
    $CurrentOrganisation->membersCount = $Organisation->countUsers();
    $CurrentOrganisation->equipesCount = $Organisation->countEquipes();

    require_once VIEWS_PATH."admin/".$tpl;
}
else
{
    header("location:".ROOT_URL."index.php");
}


?>