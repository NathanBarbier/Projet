<?php

$idOrganisation = $_SESSION["idOrganisation"] ?? false;

$Organisation = new Organisation($idOrganisation);

$nbMembresEquipes = $Organisation->CountUsersByEquipes($idOrganisation);
$nbMembresPostes = $Organisation->CountUsersByPoste($idOrganisation);
$equipeMinMax = $Organisation->getMinMaxIdEquipe($idOrganisation);
$chefEquipes = $Organisation->getInfosChefsEquipes($idOrganisation);

$Role = new Role();
$Equipe = new Equipe();
$Projet = new Projet();
$User = new User();
$Poste = new Poste();

//TODO OPTI ???
$roles = $Role->fetchAll();
$equipes = $Equipe->fetchAll($idOrganisation);
$postes = $Poste->fetchAll($idOrganisation);

foreach($fetchEquipes as $key => $equipe)
{
    $membresEquipes[$key][] = $User->fetchByEquipe($equipe["idEquipe"]);
    $projetsEquipes[$key][] = $Projet->fetchByEquipe($equipe["idEquipe"]);
}

foreach($membresEquipes as $equipekey => $equipe)
{
    foreach($equipe as $membrekey => $membre)
    {
        $membresEquipes[$equipekey][$membrekey]["poste"] = $Poste->fetch($membre["idPoste"]);
    }
}

$action = $_GET["action"] ?? false;
$error = $_GET["error"] ?? false;
$success = $_GET["success"] ?? false;
$idPoste = $_GET["idPoste"] ?? false;

$nomPoste = $_POST["nomPoste"] ?? false;
$idRole = $_POST["idRole"] ?? false;
$nomEquipe = $_POST["nomEquipe"] ?? false;

$errorMessage = false;
$successMessage = false;
$deletePoste = false;

$fetchPoste = $idPoste ? $Poste->fetch($idPoste) : false;

if($error)
{
    switch($error)
    {
        case "fatalError":
            $errorMessage = "Erreur : une erreur inconnu est survenue.";
            break;
    }
}

if($success)
{
    switch($success)
    {
        case "ajouterPoste":
            $successMessage = "Le poste a bien été ajouté.";
            break;
        case "modifierPoste":
            $successMessage = "Le poste a bien été modifié.";
            break;
        case "supprimerPoste":
            $successMessage = "Le poste a bien été supprimé.";
            break;
        case "ajouterEquipe":
            $successMessage = "L'équipe a bien été ajoutée.";
            break;
    }
}


if($action == "deletePoste")
{
    $deletePoste = true;
}

if($action == "deletePosteConf")
{
    $Poste->delete($idPoste);
}

if($action == "updatePoste")
{
    $Poste->updateName($nomPoste);
}

if($action == "addPoste")
{
    $Poste->create($nomPoste, $idOrganisation, $idRole);
}

if($action == "addEquipe")
{
    $Equipe->create($nomEquipe, $idOrganisation);
}

?>