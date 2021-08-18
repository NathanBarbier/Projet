<?php require_once "header.php";

$action = $_GET["action"] ? $_GET["action"] : false;
$idEquipe = $_GET["idEquipe"] ? $_GET["idEquipe"] : false;

$idOrganisation = $_SESSION["idOrganisation"] ? $_SESSION["idOrganisation"] : false;

$Equipe = new Equipe($idEquipe);

$equipe = $Equipe->fetch($idEquipe);
$nmMembresEquipe = $Equipe->countMembres();
$membresEquipe = $Equipe->countMembres();
$chefEquipe = $Equipe->getChef();


?>

