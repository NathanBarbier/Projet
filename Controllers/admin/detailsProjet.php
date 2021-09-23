<?php
//import all models
require_once "../../traitements/header.php";

$idOrganization = $_SESSION["idOrganization"] ?? false;
$rights = $_SESSION["rights"] ?? false;

if($rights === "admin")
{
    $tpl = "detailsProjet.php";

    $action = GETPOST('action');
    $idProject = GETPOST('idProject');

    $Project = new Project($idProject);

    $CurrentProject = new stdClass;

    $CurrentProject->name = $Project->getName();
    $CurrentProject->description = $Project->getDescritpion();
    $CurrentProject->type = $Project->getType();

    $success = false;
    $errors = array();

    //TODO récupérer tous les membres d'une organization
    //TODO qui ne sont pas attribué au projet
    // 
    $User = new User();
    $WorkTo = new WorkTo();
    $Team = new Team();

    $projectFreeUsers = $User->fetchFreeUsersByProjectId($idProject);
    $projectTeamsIds = array();
    
    $lines = $WorkTo->fetchByProjectId($idProject);
    foreach($lines as $line)
    {
        $projectTeamsIds[] = $line->fk_team;
    }

    $projectTeamsIds = implode("', '", $projectTeamsIds);

    // $projectTeams = $Equipe->

    // var_dump($projectFreeUsers, $idProjet);
    // exit;


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
