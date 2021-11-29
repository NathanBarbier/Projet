<?php
//import all models
require_once "../../services/header.php";

$idOrganization = $_SESSION["idOrganization"] ?? false;
$rights = $_SESSION["rights"] ?? false;

if($rights === 'admin')
{
    $action = GETPOST('action');
    $error = GETPOST('error');
    $success = GETPOST('success');
    $idPosition = GETPOST('idPosition');
    $positionName = GETPOST('positionName');
    $idRole = GETPOST('idRole');
    $teamName = GETPOST('teamName');
    
    $errors = array();
    $success = false;

    $deletePosition = false;
    $updatePosition = false;
    
    $tpl = "postesEquipes.php";
    
    $organization = new organization($idOrganization);
    $Role = new Role();
    $Team = new Team();
    $Project = new Project();
    $User = new User();
    $Position = new Position();

    $fetchPosition = $idPosition ? $Position->fetch($idPosition) : false;
    $usersByPositionCounter = $organization->CountUsersByPosition($idOrganization);
    
    //TODO OPTI ???
    $roles = $Role->fetchAll();
    $teams = $Team->fetchAll($idOrganization);
    $positions = $Position->fetchAll($idOrganization);
    
    foreach($teams as $key => $team)
    {
        $projectsTeams[$key][] = $Project->fetchByTeam($team->rowid);
    }
    
    if($action == "deletePosition")
    {
        $deletePosition = true;
    }
    
    if($action == "deletePositionConf")
    {
        $status = $Position->delete($idPosition, $idOrganization);

        if($status)
        {
            $success = "Le poste a bien été supprimé.";
        }
        else
        {
            $errors[] = "Une erreur inconnue est survenue."; 
        }
    }

    if($action == "updatePosition")
    {
        $updatePosition = true;
    }
    
    if($action == "updatePositionConf")
    {
        $status = $Position->updateName($positionName, $idPosition);

        if($status)
        {
            $success = "Le poste a bien été modifié.";
        }
        else
        {
            $errors[] = "Une erreur inconnue est survenue.";
        }
    }
    
    if($action == "addPosition")
    {
        $status = $Position->create($positionName, $idOrganization, $idRole);

        if($status)
        {
            $success = "Le poste a bien été ajouté.";
        }
        else
        {
            $errors[] = "Une erreur inconnue est survenue.";
        }
    }

    if($success)
    {
        $organization = new organization($idOrganization);

        $fetchPosition = $idPosition ? $Position->fetch($idPosition) : false;
        $usersByPositionCounter = $organization->CountUsersByPosition($idOrganization);
        
        $roles = $Role->fetchAll();
        $teams = $Team->fetchAll($idOrganization);
        $positions = $Position->fetchAll($idOrganization);
    }

    require_once VIEWS_PATH."admin/".$tpl;
}
else
{
    header("location:".ROOT_URL."index.php");
}



?>