<?php
//import all models
require_once "../../traitements/header.php";

$idOrganisation = $_SESSION["idOrganisation"] ?? false;
$rights = $_SESSION["habilitation"] ?? false;

if($rights === "admin")
{
    $tpl = "detailsProjet.php";

    $action = GETPOST('action');
    $idProjet = GETPOST('idProjet');

    $Project = new Projet($idProjet);

    $CurrentProject = new stdClass;

    $CurrentProject->name = $Project->getNom();
    $CurrentProject->description = $Project->getDescritpion();
    $CurrentProject->type = $Project->getType();

    $success = false;
    $erreurs = array();


    if($action == "undefined")
    {

    }

    require_once VIEWS_PATH."admin/".$tpl;
}
else
{
    header("location:".ROOT_URL."index.php");
}


?>
