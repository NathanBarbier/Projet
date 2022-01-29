<?php 
// import all models
require_once "../../services/header.php";

$rights = $_SESSION["rights"] ?? false;
$idOrganization = $_SESSION["idOrganization"] ?? null;
$idUser = $_SESSION["idUser"] ?? null;

if($rights == 'user')
{
    $action = GETPOST('action');
    $projectId = intval(GETPOST('projectId'));
    $teamId = intval(GETPOST('teamId'));

    $tpl = "map.php";
    $errors = array();
    $success = false;

    if($teamId) 
    {
        if($projectId)
        {
            $Organization = new Organization($idOrganization);
            $User = new User($idUser);

            // fetching project & team
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

            // check if the user belongs to the team
            $UserBelongsToTeam = false;
            foreach($Team->getUsers() as $User)
            {
                if($User->getRowid() == $idUser)
                {
                    $UserBelongsToTeam = true;
                    break;
                }
            }

            if(!$UserBelongsToTeam)
            {
                header("location:".ROOT_URL."index.php");
                exit;
            }

            if($action == "archiveTeam")
            {
                try {
                    $Team->setActive(0);
                    $Team->update();
                    $message = "Le tableau a bien été archivé.";
                    header("location:".CONTROLLERS_URL."membre/tableauDeBord.php?success=".$message);
                    exit;
                } catch (\Throwable $th) {
                    //throw $th;
                    $errors[] = "Une erreur innatendue est survenue.";
                }
            }

            
            if($Project->isActive() == 0)
            {
                $errors[] = "Le projet est archivé.";
                $errors = serialize($errors);
                header("location:".CONTROLLERS_URL."membre/tableauDeBord.php?errors=".$errors);
                exit;
            }

            // for JS
            $username = $User->getLastname() . " " . $User->getFirstname();

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

            ?>
            <script>
            var teamId = <?php echo json_encode($Team->getRowid()); ?>;
            const username = <?php echo json_encode($username); ?>;
            const idOrganization = <?php echo json_encode($idOrganization); ?>;
            const idUser = <?php echo json_encode($idUser); ?>;
            </script>
            <?php

            require_once VIEWS_PATH."membre/".$tpl;
        }
        else
        {
            $errors[] = "Aucune projet n'a été sélectionné.";
            $errors = serialize($errors);
            header("location:".CONTROLLERS_URL.'membre/tableauDeBord.php?errors='.$errors);
        }
    }
    else
    {
        $errors[] = "Aucune équipe n'a été sélectionnée.";
        $errors = serialize($errors);
        header("location:".CONTROLLERS_URL.'membre/tableauDeBord.php?errors='.$errors);
    }
}
else
{
    header("location:".ROOT_URL."index.php");
}
?>