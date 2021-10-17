<?php 
// import all models
require_once "../../traitements/header.php";

$rights = $_SESSION["rights"] ?? false;
$idOrganization = $_SESSION["idOrganization"] ?? null;

if($rights == 'admin')
{
    $action = GETPOST('action');
    $projectId = GETPOST('projectId');
    $columnName = GETPOST('columnName');
    $columnId = GETPOST('columnId');
    $taskId = GETPOST('taskId');
    $taskName = GETPOST('taskName');
    $taskDescription = GETPOST('taskDescription');
    $teamId = GETPOST('teamId');

    $tpl = "map.php";
    $errors = array();
    $success = false;

    if($action == "archive")
    {
        if($projectId)
        {
            $Project = new Project();

            $status = $Project->updateActive(0, $projectId);

            if($status)
            {
                $message = "Le projet a bien été archivé.";
                header("location:".CONTROLLERS_URL."membres/tableauDeBord.php?success=".$message);
                exit;
            }
            else
            {
                $errors[] = "Une erreur innatendue est survenue.";
            }
        }
        else
        {
            $errors[] = "Aucun projet n'a été sélectionné.";
        }

    }

    if($teamId)
    {
        $Organization = new Organization($idOrganization);
        $MapColumns = new MapColumns();
        $Task = new Task();  
    
        $username = $Organization->getName();
    
        $Projects = $Organization->getProjects();
    
        foreach($Projects as $Project)
        {
            if($Project->getId() == $projectId)
            {
                $CurrentProject = $Project;
            }
        }
    
        $CurrentTeamId = $teamId;
    
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
        const idOrganization = <?php echo json_encode($idOrganization); ?>;
        </script><?php
    
        require_once VIEWS_PATH."admin".DIRECTORY_SEPARATOR.$tpl;
    }
    else
    {
        $errors[] = "Aucune équipe n'a été sélectionnée.";
        $errors = serialize($errors);
        header("location:".CONTROLLERS_URL.'admin/detailsProjet.php?idProject='.$projectId.'&errors='.$errors);
    }
}
else
{
    header("location:".ROOT_URL."index.php");
}
?>