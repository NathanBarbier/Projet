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
    $User = new User($idUser);
    
    
    $action = GETPOST('action');
    $projectId = GETPOST('projectId');
    $columnName = GETPOST('columnName');
    $columnId = GETPOST('columnId');
    $taskId = GETPOST('taskId');
    $taskName = GETPOST('taskName');
    $taskDescription = GETPOST('taskDescription');

    $tpl = "map.php";
    $errors = array();
    $success = false;

    $username = $User->getLastname() . " " . $User->getFirstname();


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

    ?><script>
    const ROOT_URL = <?php echo json_encode(ROOT_URL); ?>;
    const MODELS_URL = <?php echo json_encode(MODELS_URL); ?>;
    const IMG_URL = <?php echo json_encode(IMG_URL); ?>;
    const CONTROLLERS_URL = <?php echo json_encode(CONTROLLERS_URL); ?>;
    const VIEWS_URL = <?php echo json_encode(VIEWS_URL); ?>;
    const PROCESS_URL = <?php echo json_encode(PROCESS_URL); ?>;
    const JS_URL = <?php echo json_encode(JS_URL); ?>;
    const AJAX_URL = <?php echo json_encode(AJAX_URL); ?>;
    var projectId = <?php echo json_encode($CurrentProject->getId()); ?>;
    var teamId = <?php echo json_encode($CurrentTeam->getId()); ?>;
    const username = <?php echo json_encode($username); ?>;
    const idUser = <?php echo json_encode($idUser); ?>;
    </script><?php

    require_once VIEWS_PATH."membres/".$tpl;
}
else
{
    header("location:".ROOT_URL."index.php");
}
?>