<?php
//import all models
require_once "../../traitements/header.php";

$action = GETPOST('action');

$idOrganization = $_SESSION["idOrganization"] ?? false;
$rights = $_SESSION["rights"] ?? false;

$organization = new Organization($idOrganization);

$success = false;
$errors = array();

$tpl = "listeProjets.php";
// $data = new stdClass;

if($rights === "admin")
{
    $Project = new Project();

    $Project->setidOrganization($idOrganization);

    $currentProjects = $Project->fetchAll();

    foreach($currentProjects as $key => $project)
    {
        $projectId = $project->rowid;

        // Tâches à faire
        $Task = new Task();
        $todoCounter = $Task->fetchCountTodo($projectId);
        $currentProjects[$key]->todoCounter = $todoCounter;

        // Tâches en cours
        $progressCounter = $Task->fetchCountInProgress($projectId);
        $currentProjects[$key]->progressCounter = $progressCounter;

    }

    require_once VIEWS_PATH."admin/".$tpl;
}
else
{
    header("location:".ROOT_URL."index.php");
}