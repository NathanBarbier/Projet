<?php 
// import all models
require_once "../../services/header.php";
require "layouts/head.php";

$projectId = intval(htmlspecialchars(GETPOST('projectId')));
$teamId = intval(htmlspecialchars(GETPOST('teamId')));

$tpl = "map.php";
$page = "controllers/member/".$tpl;
$errors = array();
$success = false;

if($teamId)
{
    if($projectId)
    {
        $Organization = new Organization();
        $Organization->setRowid($idOrganization);
        $Organization->fetchAllAdmins();

        $CurrentUser = new User($idUser);

        $ProjectRepository  = new ProjectRepository();
        $TeamRepository     = new TeamRepository();
        $BelongsToRepository     = new BelongsToRepository();

        if($ProjectRepository->checkIfProjectBelongsToOrganization($projectId, $idOrganization))
        {
            if($TeamRepository->checkIfTeamBelongsToProject($projectId, $teamId))
            {
                // Entirely load the team
                $Team = new Team($teamId);

                // only fetch basics project properties
                $Project = new Project();
                $Project->fetch($projectId, 0);

                // check if the user belongs to the team
                if($BelongsToRepository->checkIfUserBelongsToTeam($idUser, $teamId))
                {
                    // redirect user if the project is archived
                    if($Project->isActive())
                    {
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
                        $errors[] = "Le projet est archivé.";
                        $errors = serialize($errors);
                        header("location:".CONTROLLERS_URL."member/dashboard.php?errors=".$errors);
                        exit;
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