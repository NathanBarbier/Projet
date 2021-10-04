<?php 
// import all models
require_once "../../traitements/header.php";

$idUser = $_SESSION["idUser"] ?? false;
$rights = $_SESSION["rights"] ?? false;
$idOrganization = $_SESSION["idOrganization"] ?? null;

if($rights == 'user')
{
    $Organization = new Organization($idOrganization);
    $MapColumns = new MapColumns();
    $Task = new Task();
    
    $projectId = GETPOST('projectId');

    $action = GETPOST('action');
    
    $columnName = GETPOST('columnName');
    $columnId = GETPOST('columnId');

    $taskId = GETPOST('taskId');
    $taskName = GETPOST('taskName');
    $taskDescription = GETPOST('taskDescription');

    $tpl = "map.php";
    $errors = array();
    $success = false;


    $Projects = $Organization->getProjects();

    foreach($Projects as $Project)
    {
        if($Project->getId() == $projectId)
        {
            $CurrentProject = $Project;
        }
    }

    // GET CurrentTeam

    foreach($CurrentProject->getTeams() as $team)
    {
        foreach($team->getMembers() as $member)
        {
            if($member->getId() == $idUser)
            {
                $CurrentTeamId = $team->getId();
            }
        }
    }

    foreach($CurrentProject->getTeams() as $team)
    {
        if($team->getid() == $CurrentTeamId)
        {
            $CurrentTeam = $team;
        }
    }


    if($action == "addColumn")
    {
        if($CurrentTeamId && $columnName)
        {
            $status = $MapColumns->create($columnName, $CurrentTeamId);
        }
    }

    if($action == "addTask")
    {
        if($columnId)
        {
            $status = $Task->create($columnId);
        }
    }

    if($action == "updateTask")
    {
        if($taskId && $taskName)
        {
            $status = $Task->updateName($taskName, $taskId);
        }
    }

    if($action == "deleteTask")
    {
        if($taskId)
        {
            $status = $Task->delete($taskId);
        }
    }

    if($action == "deleteColumn")
    {
        if($columnId)
        {
            $status = array();
            
            $status[] = $Task->deleteByColumnId($columnId);
            $status[] = $MapColumns->delete($columnId);
        }
    }

    if($action == "renameColumn")
    {
        if($columnId)
        {

        }
    }




    require_once VIEWS_PATH."membres/".$tpl;
}
else
{
    header("location:".ROOT_URL."index.php");
}
?>