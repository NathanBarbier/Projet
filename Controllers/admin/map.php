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

        $CurrentUser = new User($idUser);

        $ProjectRepository  = new ProjectRepository();
        $TeamRepository     = new TeamRepository();

        if($ProjectRepository->checkIfProjectBelongsToOrganization($projectId, $idOrganization))
        {
            if($TeamRepository->checkIfTeamBelongsToProject($projectId, $teamId))
            {
                // Entirely load the team
                $Team = new Team($teamId);

                $Project = new Project();
                $Project->fetch($projectId, 0);

                if($action == "archiveTeam")
                {
                    try {
                        $Team->setActive(0);
                        $Team->update();
                        LogHistory::create($idOrganization, $idUser, "WARNING", 'archive', 'team', $Team->getName(), null, 'team id : '.$Team->getRowid(), null, $ip);
                        $success = "Le tableau a bien été archivé.";
                    } catch (\Throwable $th) {
                        $errors[] = "Une erreur innatendue est survenue.";
                        LogHistory::create($idOrganization, $idUser, "ERROR", 'archive', 'team', $Team->getName(), null, 'team id : '.$Team->getRowid(), $th->getMessage(), $ip);
                    }
                }
    
                if($action == "openTeam")
                {
                    try {
                        $Team->setActive(1);
                        $Team->update();
                        LogHistory::create($idOrganization, $idUser, "INFO", 'unarchive', 'team', $Team->getName(), null, 'team id : '.$Team->getRowid(), null, $ip);
                        $success = "Le tableau a bien été ré-ouvert.";
                    } catch (\Throwable $th) {
                        $errors[] = "Une erreur innatendue est survenue.";
                        LogHistory::create($idOrganization, $idUser, "ERROR", 'unarchive', 'team', $Team->getName(), null, 'team id : '.$Team->getRowid(), $th->getMessage(), $ip);
                    }
                }
            
                if($action == "openProject")
                {
                    try {
                        $Project->setActive(1);
                        $Project->update();
                        LogHistory::create($idOrganization, $idUser, "WARNING", 'unarchive', 'project', $Project->getName(), null, 'project id : '.$Project->getRowid(), null, $ip);
                        $success = "Le projet à bien été ré-ouvert.";
                    } catch (\Throwable $th) {
                        $errors[] = "Une erreur innatendue est survenue.";
                        LogHistory::create($idOrganization, $idUser, "ERROR", 'unarchive', 'project', '', null, 'project id : '.$Project->getRowid(), $th->getMessage(), $ip);
                    }
                }
    
                // for JS
                $username = $CurrentUser->getLastname() . ' ' . $CurrentUser->getFirstname();

                if(strlen(trim($username)) == 0) 
                {
                    $username = $Organization->getName();
                }
    
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
                            // all team users + current admin
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
                var projectId = <?php echo json_encode($Project->getRowid()); ?>;
                var notificationCount = <?php echo json_encode($notificationCount); ?>;
                const username = <?php echo json_encode($username); ?>;
                const idOrganization = <?php echo json_encode($idOrganization); ?>;
                const idUser = <?php echo json_encode($idUser); ?>;
                </script>
                <?php
            
                require_once VIEWS_PATH."admin/".$tpl;
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
        header("location:".ROOT_URL."index.php");
    }
}
else
{
    $errors[] = "Aucune équipe n'a été sélectionnée.";
    $errors = serialize($errors);
    header("location:".CONTROLLERS_URL.'admin/projectDashboard.php?idProject='.$projectId.'&errors='.$errors);
}
?>