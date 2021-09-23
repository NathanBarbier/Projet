<?php
require_once "../../traitements/header.php";

$idOrganisation = $_SESSION["idOrganisation"] ?? false;
$rights = $_SESSION["habilitation"] ?? false;

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