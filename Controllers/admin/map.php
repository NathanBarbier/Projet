<?php 
// import all models
require_once "../../services/header.php";

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

    if($teamId)
    {
        $Project = new Project();
        $MapColumn = new MapColumn();
        $Task = new Task();  


        if($action == "archiveTeam")
        {
            if($projectId)
            {   
                $Team = new Team();
                $status = $Team->updateActive(0, $teamId);
    
                if($status)
                {
                    $success = "Le tableau a bien été archivé.";
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

        if($action == "openTeam")
        {
            if($projectId)
            {   
                $Team = new Team();
                $status = $Team->updateActive(1, $teamId);
    
                if($status)
                {
                    $success = "Le tableau a bien été ré-ouvert.";
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
    
        if($action == "openProject")
        {
            if($projectId)
            {
                $status = $Project->updateActive(true, $projectId);
    
                if($status)
                {
                    $success = "Le projet à bien été ré-ouvert.";
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

        $Organization = new Organization($idOrganization);
    
        $username = $Organization->getName();
    
        $Projects = $Organization->getProjects();
    
        foreach($Projects as $Project)
        {
            if($Project->getId() == $projectId)
            {
                $CurrentProject = $Project;
                break;
            }
        }
    
        $CurrentTeamId = $teamId;
    
        foreach($CurrentProject->getTeams() as $team)
        {
            if($team->getid() == $CurrentTeamId)
            {
                $CurrentTeam = $team;
                break;
            }
        }

        $authors = array();
        $usernames = array();

        foreach($CurrentTeam->getMembers() as $member)
        {
            $usernames[$member->getId()] = $member->getLastname() . ' ' . $member->getFirstname();
        }

        foreach($CurrentTeam->getMapColumns() as $columnKey => $column)
        {
            foreach($column->getTasks() as $taskKey => $task)
            {
                if($task->getAdmin() == 1)
                {
                    $authors[$columnKey][$taskKey] = $Organization->getName();
                }
                else
                {
                    $authors[$columnKey][$taskKey] = $usernames[$task->getFk_author()];
                }
            }
        }

        // notification count
        $notificationCount = 0;
        if($CurrentTeam->getActive() == 0) {
            $notificationCount++;
        }
        if($CurrentProject->getActive() == 0) {
            $notificationCount++;
        }

        ?><script>
        const ROOT_URL = <?php echo json_encode(ROOT_URL); ?>;
        const MODELS_URL = <?php echo json_encode(MODELS_URL); ?>;
        const IMG_URL = <?php echo json_encode(IMG_URL); ?>;
        const CONTROLLERS_URL = <?php echo json_encode(CONTROLLERS_URL); ?>;
        const VIEWS_URL = <?php echo json_encode(VIEWS_URL); ?>;
        const SERVICES_URL = <?php echo json_encode(SERVICES_URL); ?>;
        const JS_URL = <?php echo json_encode(JS_URL); ?>;
        const AJAX_URL = <?php echo json_encode(AJAX_URL); ?>;
        var projectId = <?php echo json_encode($CurrentProject->getId()); ?>;
        var teamId = <?php echo json_encode($CurrentTeam->getId()); ?>;
        var notificationCount = <?php echo json_encode($notificationCount); ?>;
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