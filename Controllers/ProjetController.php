<?php require_once "header.php";

$idOrganisation = $_SESSION["idOrganisation"] ? $_SESSION["idOrganisation"] : false;

$action = $_GET["action"] ? $_GET["action"] : false;
$idProjet = $_GET["idProjet"] ? $_GET["idProjet"] : false;

$titre = $_POST["titre"] ? $_POST["titre"] : false;
$type = $_POST["type"] ? $_POST["type"] : false;
$deadline = $_POST["deadline"] ? $_POST["deadline"] : false;
$idClient = $_POST["idClient"] ? $_POST["idClient"] : false;
$chefProjet = $_POST["chefProjet"] ? $_POST["chefProjet"] : false;
$description = $_POST["description"] ? $_POST["description"] : false;

$Equipe = new Equipe();
$Client = new Client();
$Projet = new Projet();

$equipes = $Equipe->fetchAll($idOrganisation);
$clients = $Client->fetchAll($idOrganisation);

foreach($equipes as $key => $equipe)
{
    $chefsEquipes[$key][] = $Equipe->fetchChef($equipe["idEquipe"]);
}

$maxIdProjet = $Projet->fetchMaxId()["maxId"];

if($action == "addProjet")
{
    $Projet->create($titre, $type, $deadline, $idClient, $chefProjet, $description);
}



?>