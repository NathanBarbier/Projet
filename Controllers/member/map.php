<?php 
// import all models
require_once "../../services/header.php";
require "layouts/head.php";

$action = htmlentities(GETPOST('action'));
$projectId = intval(GETPOST('projectId'));
$teamId = intval(GETPOST('teamId'));

$tpl = "map.php";
$errors = array();
$success = false;

if($teamId)
{
    if($projectId)
    {
        $Organization = new Organization();
        $Organization->setRowid($idOrganization);
        $Organization->fetchProjects(0);
        $Organization->fetchAllUsers();

        $CurrentUser = new User($idUser);

        // fetching project & team
        foreach($Organization->getProjects() as $Obj)
        {
            if($Obj->getRowid() == $projectId)
            {
                $Project = $Obj;
                break;
            }
        }

        // check if the Team & Project exists
        if(!empty($Project))
        {
            // fetch The project teams
            $Project->fetchTeams(0);

            // check if the given team belongs to this project
            foreach($Project->getTeams() as $Obj)
            {
                if($Obj->getRowid() == $teamId)
                {
                    $Team = $Obj;
                    break;
                }
            }

            if(!empty($Team))
            {
                // Fetch entirely the team
                $Team->fetch($Team->getRowid());

                // check if the user belongs to the team
                $UserBelongsToTeam = false;
                foreach($Team->getUsers() as $TeamUser)
                {
                    if($TeamUser->getRowid() == $idUser)
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

                // redirect user if the project is archived
                if($Project->isActive() == 0)
                {
                    $errors[] = "Le projet est archivé.";
                    $errors = serialize($errors);
                    header("location:".CONTROLLERS_URL."member/dashboard.php?errors=".$errors);
                    exit;
                }

                if($action == "archiveTeam")
                {
                    try {
                        $Team->setActive(0);
                        $Team->update();
                        LogHistory::create($idOrganization, $idUser, "WARNING", 'archive', 'team', $Team, '', 'team id : '.$Team->getRowid());
                        $message = "Le tableau a bien été archivé.";
                        header("location:".CONTROLLERS_URL."member/dashboard.php?success=".$message);
                        exit;
                    } catch (\Throwable $th) {
                        $errors[] = "Une erreur innatendue est survenue.";
                        LogHistory::create($idOrganization, $idUser, "ERROR", 'archive', 'team', $Team, '', 'team id : '.$Team->getRowid(), $th);
                    }
                }

                // for JS
                $username = $CurrentUser->getLastname() . ' ' . $CurrentUser->getFirstname();

                $authors = array();
                $usernames = array();

                // Get tasks authors for JS
                foreach($Team->getUsers() as $TeamUser)
                {
                    $usernames[$TeamUser->getRowid()] = $TeamUser->getLastname() . ' ' . $TeamUser->getFirstname();
                }
    
                foreach($Team->getMapColumns() as $columnKey => $Column)
                {
                    if(!empty($Column->getTasks()))
                    {
                        foreach($Column->getTasks() as $taskKey => $Task)
                        {
                            // all team users
                            $TeamUsers = $Team->getUsers();
                            
                            // get all organization admins
                            foreach($Organization->getUsers() as $TeamUser)
                            {
                                if($TeamUser->isAdmin())
                                {
                                    $usernames[$TeamUser->getRowid()] = $TeamUser->getLastname() . ' ' . $TeamUser->getFirstname();
                                    $TeamUsers[] = $TeamUser;
                                }
                            }
        
                            // verify that fk_author correspond to an admin user
                            foreach($TeamUsers as $TeamUser)
                            {
                                if($TeamUser->getRowid() == $Task->getFk_user())
                                {
                                    $authors[$columnKey][$taskKey] = $usernames[$Task->getFk_user()] ?? ($TeamUser->isAdmin() ? $Organization->getName() : '');
                                    break;
                                }
                            }
                        }
                    }
                }

                ?>
                <script>
                var teamId = <?php echo json_encode($Team->getRowid()); ?>;
                var projectId = <?php echo json_encode($Project->getRowid()); ?>;
                const username = <?php echo json_encode($username); ?>;
                const idOrganization = <?php echo json_encode($idOrganization); ?>;
                const idUser = <?php echo json_encode($idUser); ?>;
                </script>
                <?php

                require_once VIEWS_PATH."member/".$tpl;
            }
            else
            {
                header("location:".ROOT_URL."index.php"); 
            }
        }
        else
        {
            header("location:".ROOT_URL."index.php");
        }
    }
    else
    {
        $errors[] = "Aucune projet n'a été sélectionné.";
        $errors = serialize($errors);
        header("location:".CONTROLLERS_URL.'member/dashboard.php?errors='.$errors);
    }
}
else
{
    $errors[] = "Aucune équipe n'a été sélectionnée.";
    $errors = serialize($errors);
    header("location:".CONTROLLERS_URL.'member/dashboard.php?errors='.$errors);
}
?>