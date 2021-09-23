<?php
//import all models
require_once "../../traitements/header.php";

$action = GETPOST('action');

$idOrganisation = $_SESSION["idOrganisation"] ?? false;
$rights = $_SESSION["habilitation"] ?? false;

$Organisation = new Organisation($idOrganisation);

$success = false;
$erreurs = array();

$tpl = "listeProjets.php";
// $data = new stdClass;

if($rights === "admin")
{

    //TODO récuperer les projets actuels en rapport avec l'organisation

    $Project = new Projet();

    var_dump($idOrganisation);

    $Project->setIdOrganisation($idOrganisation);

    $currentProjects = $Project->fetchAll();

    foreach($currentProjects as $key => $project)
    {
        $projectId = $project->idProjet;

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