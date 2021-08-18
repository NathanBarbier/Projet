<?php require_once "header.php";

$equipes = recupererEquipes($_SESSION['idOrganisation']);
$clients = recupererClientOrganisation($_SESSION['idOrganisation']);
if(!empty($_GET['idProjet']))
{
    extract($_GET);
}
else
{
    $idProjet = recupMaxIdProjets();
    $idProjet = $idProjet['maxId'] + 1;
}


?>