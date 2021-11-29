<?php
require_once "../../services/header.php";

$idOrganization = $_SESSION["idOrganization"] ?? false;
$rights = $_SESSION["rights"] ?? false;

if($rights === "admin")
{
    $idEquipe = GETPOST('idEquipe');
    
    $Equipe = new Equipe($idEquipe);
    
    $tpl = "infoEquipe.php";

    $CurrentEquipe = new stdClass;

    $CurrentEquipe->id = $Equipe->getId();
    $CurrentEquipe->nom = $Equipe->getNom();
    $CurrentEquipe->membres = $Equipe->getMembres();

    require_once VIEWS_PATH."admin/".$tpl;
}
else
{
    header("location:".ROOT_URL."index.php");
}


?>