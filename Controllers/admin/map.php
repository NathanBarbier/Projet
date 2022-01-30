<?php 
// import all models
require_once "../../services/header.php";

$rights = $_SESSION["rights"] ?? false;
$idOrganization = $_SESSION["idOrganization"] ?? null;
$idUser = $_SESSION["idUser"] ?? null;

if($rights == 'admin')
{
    $action = GETPOST('action');
    $projectId = intval(GETPOST('projectId'));
    $teamId = intval(GETPOST('teamId'));

    $tpl = "map.php";
    $errors = array();
    $success = false;

    if($teamId && $projectId)
    {
        $Organization = new Organization($idOrganization);

        foreach($Organization->getProjects() as $Obj)
        {
            if($Obj->getRowid() == $projectId)
            {
                $Project = $Obj;
                break;
            }
        }

        foreach($Project->getTeams() as $Obj)
        {
            if($Obj->getRowid() == $teamId)
            {
                $Team = $Obj;
                break;
            }
        }

        if($action == "archiveTeam")
        {
            if($projectId)
            {   
                try {
                    $Team->setActive(0);
                    $Team->update();
                    LogHistory::create($idUser, 'archive', 'team', $Team->getName());
                    $success = "Le tableau a bien été archivé.";
                } catch (\Throwable $th) {
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
                try {
                    $Team->setActive(1);
                    $Team->update();
                    LogHistory::create($idUser, 'unarchive', 'team', $Team->getName());
                    $success = "Le tableau a bien été ré-ouvert.";
                } catch (\Throwable $th) {
                    //throw $th;
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
                try {
                    $Project->setActive(1);
                    $Project->update();
                    LogHistory::create($idUser, 'unarchive', 'project', $Project->getName());
                    $success = "Le projet à bien été ré-ouvert.";
                } catch (\Throwable $th) {
                    //throw $th;
                    $errors[] = "Une erreur innatendue est survenue.";
                }
            }
            else
            {
                $errors[] = "Aucun projet n'a été sélectionné.";
            }
        }

        // for JS
        $username = $Organization->getName();

        $authors = array();
        $usernames = array();

        // Get tasks authors for JS
        foreach($Team->getUsers() as $User)
        {
            $usernames[$User->getRowid()] = $User->getLastname() . ' ' . $User->getFirstname();
        }

        foreach($Team->getMapColumns() as $columnKey => $Column)
        {
            foreach($Column->getTasks() as $taskKey => $Task)
            {
                // all team users + current admin
                $TeamUsers = $Team->getUsers();
                
                // get all organization admins
                foreach($Organization->getUsers() as $User)
                {
                    if($User->isAdmin())
                    {
                        $TeamUsers[] = $User;
                    }
                }

                // verify that fk_author correspond to an admin user
                foreach($TeamUsers as $User)
                {
                    if($User->getRowid() == $Task->getFk_user())
                    {
                        if($User->isAdmin())
                        {
                            $authors[$columnKey][$taskKey] = $Organization->getName();
                        }
                        else
                        {
                            $authors[$columnKey][$taskKey] = $usernames[$task->getFk_author()];
                        }
                        break;
                    }
                }
            }
        }

        // notification count
        $notificationCount = 0;
        if($Team->isActive() == 0) {
            $notificationCount++;
        }
        if($Project->isActive() == 0) {
            $notificationCount++;
        }

        ?>
        <script>
        var teamId = <?php echo json_encode($Team->getRowid()); ?>;
        var notificationCount = <?php echo json_encode($notificationCount); ?>;
        const username = <?php echo json_encode($username); ?>;
        const idOrganization = <?php echo json_encode($idOrganization); ?>;
        const idUser = <?php echo json_encode($idUser); ?>;
        </script>
        <?php
    
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