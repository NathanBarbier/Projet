<?php
//import all models

require_once "../../services/header.php";
require "layouts/head.php";

$tpl = "projectDashboard.php";
$page = CONTROLLERS_URL."admin/".$tpl;

$action      = htmlentities(GETPOST('action'));
$idProject   = intval(htmlentities(GETPOST('idProject')));
$projectName = htmlentities(GETPOST('projectName'));
$description = htmlentities(GETPOST('description'));
$type        = htmlentities(GETPOST('type'));
$teamName       = htmlentities(GETPOST('teamName'));
$teamNameUpdate = htmlentities(GETPOST('teamNameUpdate'));
$teamId         = intval(htmlentities(GETPOST('teamId')));
$errors         = GETPOST('errors');

$offset = 30;
$success = false;

if($errors) {
    $errors = unserialize($errors);
} else {
    $errors = array();
}

if($idProject)
{
    // fetch all organization projects with only teams
    $Organization = new Organization();
    $Organization->setRowid($idOrganization);
    $Organization->fetchUsers();

    $ProjectRepository = new ProjectRepository();
    $UserRepository = new UserRepository();

    if(!empty($Organization) && $ProjectRepository->checkIfProjectBelongsToOrganization($idProject, $idOrganization))
    {
        $User       = new User();
        $Team       = new Team($teamId);
        $BelongsTo  = new BelongsTo();

        $Project    = new Project();
        $Project->fetch($idProject, 0);
        $Project->fetchTeams(1);

        /*************************
        *       Free users       *
        **************************/

        $freeUsersIds = array();

        foreach($Organization->getUsers() as $OrganizationUser)
        {
            // if is admin
            if($OrganizationUser->isAdmin())
            {
                continue;
            }

            // if already to a team
            foreach($Project->getTeams() as $ProjectTeam)
            {
                foreach($ProjectTeam->getUsers() as $TeamUser)
                {
                    if($TeamUser->getRowid() == $OrganizationUser->getRowid())
                    {
                        continue 3;
                    }
                }
            }

            // all checks passed
            $freeUsersIds[] = $OrganizationUser->getRowid();
        }


        /************************
        *        Actions        *
        *************************/

        if($action == 'addTeam' || $action == "updateTeam")
        {
            $addingUsersIds = array();

            // Get all $_POST matching the pattern 'addingUser'
            foreach($_POST as $key => $post)
            {
                if('addingUser' == substr($key, 0, 10))
                {
                    $addingUsersIds[] = intval($post);
                }
            }
        }

        if($action == "archiveTeam")
        {
            if($teamId && $Project->checkTeam($teamId))
            {
                try {
                    $Team->setActive(0);
                    $Team->update();
                    LogHistory::create($idUser, 'archive', 'team', $teamId, 'project', $Project->getRowid(), $idOrganization, "WARNING", null, $ip, $page);
                    $success = "Le tableau de l'équipe à bien été archivé.";
                } catch (\Throwable $th) {
                    $errors[] = "Une erreur innatendue est survenue.";
                    LogHistory::create($idUser, 'archive', 'team', $teamId, 'project', $Project->getRowid(), $idOrganization, "ERROR", $th->getMessage(), $ip, $page);
                }
            }
            else
            {
                $errors[] = "Vous n'avez pas sélectionné d'équipe.";
            }
        }

        if($action == "openTeam")
        {
            if($teamId && $Project->checkTeam($teamId))
            {
                try {
                    $Team->setActive(1);
                    $Team->update();
                    LogHistory::create($idUser, 'unarchive', 'team', $teamId, 'project', $Project->getRowid(), $idOrganization, "WARNING", null, $ip, $page);
                    $success = "Le tableau de l'équipe à bien été ré-ouvert.";
                } catch (\Throwable $th) {
                    $errors[] = "Une erreur innatendue est survenue.";
                    LogHistory::create($idUser, 'unarchive', 'team', $teamId, 'project', $Project->getRowid(), $idOrganization, "ERROR", $th->getMessage(), $ip, $page);
                }
            }
            else
            {
                $errors[] = "Vous n'avez pas sélectionné d'équipe.";
            }
        }

        if($action == "updateProject")
        {
            if($projectName && $description && $type)
            {
                try 
                {
                    $Project->setName($projectName);
                    $Project->setDescription($description);
                    $Project->setType($type);
                    $Project->update();
                    LogHistory::create($idUser, 'update', 'project', $Project->getRowid(), null, null, $idOrganization, "INFO", null, $ip, $page);
                    $success = "Les informations du projet ont bien été mises à jour.";
                } 
                catch (\Throwable $th) 
                {
                    $errors[] = "Une erreur inattendue est survenue.";
                    LogHistory::create($idUser, 'update', 'project', $Project->getRowid(), null, null, $idOrganization, "ERROR", $th->getMessage(), $ip, $page);
                }
            }
            else
            {
                $errors[] = "Tous les champs ne sont pas remplis.";
            }
        }
        
        if($action == "addTeam")
        {
            if($teamName)
            {
                if($addingUsersIds)
                {
                    try {
                        $Team = new Team();
                        $Team->setName($teamName);
                        $Team->setFk_project($idProject);
                        $Team->setActive(1);
                        $lastInsertedId = $Team->create();
                        LogHistory::create($idUser, 'create', 'team', $lastInsertedId, "project", $Project->getRowid(), $idOrganization, "INFO", null, $ip, $page);

                        $teamId = $Team->fetchMaxId()->rowid;
    
                        $Team->setRowid($teamId);

                        // check if the users can be affected to the team
                        foreach($addingUsersIds as $idUserToAdd)
                        {
                            // check if the user belongs to the organization
                            $belongs = $UserRepository->checkIfUserBelongsToOrganization($idOrganization, $idUserToAdd);
                            if(!$belongs)
                            {
                                continue;  
                            }

                            // check if the user already belongs to a team from this project
                            foreach($Project->getTeams() as $ProjectTeam)
                            {
                                foreach($ProjectTeam->getUsers() as $TeamUser)
                                {
                                    if($TeamUser->getRowid() == $idUserToAdd)
                                    {
                                        // can't be added
                                        continue 3;
                                    }
                                }
                            }

                            // check if the user is an admin (can't belong)
                            $userToAdd = new User($idUserToAdd);
                            if($userToAdd->isAdmin())
                            {
                                continue;
                            }

                            // remove user from free users
                            foreach($freeUsersIds as $key => $freeUserId)
                            {
                                if($freeUserId == $idUserToAdd)
                                {
                                    unset($freeUsersIds[$key]);
                                }
                            }

                            // User is passed through all checks
                            // affect him to the team
                            $Team->addUser($userToAdd);

                            $BelongsTo->setFk_user($idUserToAdd);
                            $BelongsTo->setFk_team($teamId);

                            $BelongsTo->create();
                        }

                        $Project->addTeam($Team);

                        $success = "L'équipe a bien été créée.";
                    } catch (\Throwable $th) {
                        $errors[] = "Une erreur inattendue est survenue.";
                        LogHistory::create($idUser, 'create', 'team', null, "project", $Project->getRowid(), $idOrganization, "ERROR", $th->getMessage(), $ip, $page);
                    }
                }
                else
                {
                    try {
                        // create team without users
                        $Team = new Team();
                        $Team->setName($teamName);
                        $Team->setFk_project($idProject);
                        $Team->setActive(1);
                        $lastInsertedId = $Team->create();

                        $teamId = $Team->fetchMaxId()->rowid;
                        $Team->setRowid($teamId);
                        LogHistory::create($idUser, 'create', 'team', $lastInsertedId, "project", $Project->getRowid(), $idOrganization, "INFO", null, $ip, $page);

                        $Project->addTeam($Team);

                        $success = "L'équipe a bien été créée.";
                    } catch (\Throwable $th) {
                        $errors[] = "Une erreur inattendue est survenue.";
                        LogHistory::create($idUser, 'create', 'team', null, "project", $Project->getRowid(), $idOrganization, "ERROR", $th->getMessage(), $ip, $page);
                    }
                }
            }
            else
            {
                $errors[] = "L'équipe n'a pas de nom.";
            }
        }

        if($action == "deleteTeam")
        {
            if($teamId && $Project->checkTeam($teamId))
            {
                try {
                    $Team->delete($teamId);
                    LogHistory::create($idUser, 'delete', 'team', $teamId, "project", $Project->getRowid(), $idOrganization, "IMPORTANT", null, $ip, $page);

                    $Project->removeTeam($teamId);

                    // remove user from free users
                    foreach($freeUsersIds as $key => $freeUserId)
                    {
                        foreach($Team->getUsers() as $TeamUser)
                        {
                            if($freeUserId == $TeamUser->getRowid())
                            {
                                unset($freeUsersIds[$key]);
                            }
                        }
                    }

                    $success = "L'équipe a bien été supprimée.";
                } catch (\Throwable $th) {
                    $errors[] = "Une erreur innatendue est survenue.";
                    LogHistory::create($idUser, 'delete', 'team', $teamId, "project", $Project->getRowid(), $idOrganization, "IMPORTANT", $th->getMessage(), $ip, $page);
                }
            }
            else
            {
                $errors[] = "Vous n'avez pas sélectionné d'équipe";
            }
        }

        if($action == "updateTeam")
        {
            if($teamId && $Project->checkTeam($teamId))
            {
                try 
                {
                    // Team name update
                    if($teamNameUpdate)
                    {
                        $Team->setName($teamNameUpdate);
                    }

                    // check if the users can be affected to the team
                    foreach($addingUsersIds as $idUserToAdd)
                    {
                        // check if the user belongs to the organization
                        $belongs = $UserRepository->checkIfUserBelongsToOrganization($idOrganization, $idUserToAdd);
                        if(!$belongs)
                        {
                            continue;  
                        }

                        // check if the user already belongs to a team from this project
                        foreach($Project->getTeams() as $ProjectTeam)
                        {
                            foreach($ProjectTeam->getUsers() as $TeamUser)
                            {
                                if($TeamUser->getRowid() == $idUserToAdd)
                                {
                                    // can't be added
                                    continue 3;
                                }
                            }
                        }

                        // check if the user is an admin (can't belong)
                        $userToAdd = new User($idUserToAdd);
                        if($userToAdd->isAdmin())
                        {
                            continue;
                        }

                        // User is passed through all checks
                        // affect him to the team
                        $Team->addUser($userToAdd);

                        $BelongsTo->setFk_user($idUserToAdd);
                        $BelongsTo->setFk_team($teamId);

                        $BelongsTo->create();

                        // remove user from free users
                        foreach($freeUsersIds as $key => $freeUserId)
                        {
                            if($freeUserId == $idUserToAdd)
                            {
                                unset($freeUsersIds[$key]);
                            }
                        }
                    }

                    // remove team users
                    foreach($Team->getUsers() as $TeamUser)
                    {
                        $fk_user = intval(GETPOST('removingUser'.$TeamUser->getRowid()));

                        if($fk_user && $TeamUser->getRowid() == $fk_user)
                        {
                            $BelongsTo = new BelongsTo();
                            
                            $BelongsTo->setFk_user($fk_user);
                            $BelongsTo->setFk_team($Team->getRowid());

                            $BelongsTo->delete();

                            $Team->removeUser($TeamUser->getRowid());

                            // add user to free users
                            $freeUsersIds[] = $TeamUser->getRowid();
                        }
                    }

                    // update project -> team object
                    $Project->removeTeam($Team->getRowid());
                    $Project->addTeam($Team);
                    
                    $Team->update();
                    LogHistory::create($idUser, 'update', 'team', $Team->getRowid(), "project", $Project->getRowid(), $idOrganization, "INFO", null, $ip, $page);
                    $success = "L'équipe a bien été modifiée.";
                } 
                catch (\Throwable $th) 
                {
                    $errors[] = "Une erreur est survenue.";
                    LogHistory::create($idUser, 'update', 'team', $Team->getRowid(), "project", $Project->getRowid(), $idOrganization, "ERROR", $th->getMessage(), $ip, $page);
                }
            }
            else
            {
                $errors[] = "Vous n'avez pas sélectionné d'équipe";
            }

        }

        if($action == "archive")
        {
            if($idProject)
            {  
                try {
                    $Project->setActive(0);
                    $Project->update();
                    LogHistory::create($idUser, 'archive', 'project', $idProject, null, null, $idOrganization, "WARNING", null, $ip, $page);
                    $success = 'Le projet a bien été archivé.';
                } catch (\Throwable $th) {
                    $errors[] = "Une erreur innatendue est survenue.";
                    LogHistory::create($idUser, 'archive', 'project', $idProject, null, null, $idOrganization, "ERROR", $th->getMessage(), $ip, $page);
                }
            }
            else
            {
                $errors[] = "Aucun projet n'a été sélectionné.";
            }
        }

        if($action == "unarchive")
        {
            if($idProject)
            {  
                try {
                    $Project->setActive(1);
                    $Project->update();

                    $success = "Le projet a bien été désarchivé.";
                    LogHistory::create($idUser, 'unarchive', 'project', $idProject, null, null, $idOrganization, "WARNING", null, $ip, $page);
                } catch (\Throwable $th) {
                    $errors[] = "Une erreur innatendue est survenue.";
                    LogHistory::create($idUser, 'unarchive', 'project', $idProject, null, null, $idOrganization, "WARNING", $th->getMessage(), $ip, $page);
                }
            }
            else
            {
                $errors[] = "Aucun projet n'a été sélectionné.";
            }
        }

        /*********************
        *      For View      *
        **********************/

        $freeUsers = array();
        foreach($Organization->getUsers() as $OrganizationUser)
        {
            if(in_array($OrganizationUser->getRowid(), $freeUsersIds))
            {
                $freeUsers[] = $OrganizationUser;
            }
        }

        /*********************
        *   For Javascript   *
        **********************/

        $teamIds = array();

        foreach($Project->getTeams() as $ProjectTeam)
        {
            $teamIds[] = $ProjectTeam->getRowid();
        }

        ?>
        <script>
        const projectId = <?php echo json_encode($Project->getRowid()); ?>;
        var teamIds = <?php echo json_encode($teamIds); ?>;
        // use of array_values to avoid JS object conversion
        var freeUsersIds = <?php echo json_encode(array_values($freeUsersIds)); ?>;
        var Project = <?php echo json_encode($Project->object_to_array($Project)); ?>;
        </script>
        <?php
    }
    else
    {
        header("location:".ROOT_URL."index.php");
    }
}
else
{
    $errors[] = "Aucun projet n'a été sélectionné.";
}

// offset for loadmore ?>
<script>
var offset = <?php echo json_encode($offset); ?>;
</script>
<?php

require_once VIEWS_PATH."admin/".$tpl;
?>
