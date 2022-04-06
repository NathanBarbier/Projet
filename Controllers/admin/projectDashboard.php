<?php
//import all models

use phpDocumentor\Reflection\DocBlock\Tags\Var_;

require_once "../../services/header.php";
require "layouts/head.php";

$tpl = "projectDashboard.php";

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
                    LogHistory::create($idOrganization, $idUser, "WARNING", 'archive', 'team board', $Team->getName(), null, 'team id : '.$teamId, null, $ip);
                    $success = "Le tableau de l'équipe à bien été archivé.";
                } catch (\Throwable $th) {
                    $errors[] = "Une erreur innatendue est survenue.";
                    LogHistory::create($idOrganization, $idUser, "ERROR", 'archive', 'team board', $Team->getName(), null, 'team id : '.$teamId, $th->getMessage(), $ip);
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
                    LogHistory::create($idOrganization, $idUser, "WARNING", 'unarchive', 'team board', $Team->getName(), null, 'team id : '.$teamId, null, $ip);
                    $success = "Le tableau de l'équipe à bien été ré-ouvert.";
                } catch (\Throwable $th) {
                    $errors[] = "Une erreur innatendue est survenue.";
                    LogHistory::create($idOrganization, $idUser, "ERROR", 'unarchive', 'team board', $Team->getName(), null, 'team id : '.$teamId, $th->getMessage(), $ip);
                }
            }
            else
            {
                $errors[] = "Vous n'avez pas sélectionné d'équipe.";
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
                LogHistory::create($idOrganization, $idUser, "ERROR", 'unarchive', 'project', $Project->getName(), null, 'project id : '.$Project->getRowid(), $th, $ip);
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
                    LogHistory::create($idOrganization, $idUser, "INFO",'update', 'project', $Project->getName(), null, 'project id : '.$Project->getRowid(), null, $ip);
                    $success = "Les informations du projet ont bien été mises à jour.";
                } 
                catch (\Throwable $th) 
                {
                    $errors[] = "Une erreur inattendue est survenue.";
                    LogHistory::create($idOrganization, $idUser, "ERROR",'update', 'project', $Project->getName(), null, 'project id : '.$Project->getRowid(), $th->getMessage(), $ip);
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
                        LogHistory::create($idOrganization, $idUser, "INFO",'create', 'team', $Team->getName(), null, 'team id : '.$lastInsertedId, null, $ip);

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
                        LogHistory::create($idOrganization, $idUser, "ERROR",'create', 'team', $Team->getName(), null, null, $th->getMessage(), $ip);
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
                        LogHistory::create($idOrganization, $idUser, "INFO",'create', 'team', $Team->getName(), null, $lastInsertedId, null, $ip);

                        $Project->addTeam($Team);

                        $success = "L'équipe a bien été créée.";
                    } catch (\Throwable $th) {
                        $errors[] = "Une erreur inattendue est survenue.";
                        LogHistory::create($idOrganization, $idUser, "ERROR",'create', 'team', $Team->getName(), null, null, $th->getMessage(), $ip);
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
                    LogHistory::create($idOrganization, $idUser, "IMPORTANT",'delete', 'team', $Team->getName(), null, 'team id : '.$teamId, null, $ip);
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
                    LogHistory::create($idOrganization, $idUser, "ERROR",'delete', 'team', $Team->getName(), null, 'team id : '.$teamId, $th->getMessage(), $ip);
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
                    LogHistory::create($idOrganization, $idUser, "INFO",'update', 'team', $Team->getName(), null, 'team id : '.$teamId, null, $ip);
                    $success = "L'équipe a bien été modifiée.";
                } 
                catch (\Throwable $th) 
                {
                    $errors[] = "Une erreur est survenue.";
                    LogHistory::create($idOrganization, $idUser, "ERROR",'update', 'team', $Team->getName(), null, 'team id : '.$teamId, $th->getMessage(), $ip);
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
                    LogHistory::create($idOrganization, $idUser, "WARNING", $action, 'project', $Project->getName(), null, 'project id : '.$idProject, null, $ip);
                    $success = 'Le projet a bien été archivé.';
                } catch (\Throwable $th) {
                    $errors[] = "Une erreur innatendue est survenue.";
                    LogHistory::create($idOrganization, $idUser, "ERROR", $action, 'project', $Project->getName(), null, 'project id : '.$idProject, $th->getMessage(), $ip);
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
                    LogHistory::create($idOrganization, $idUser, "WARNING", $action, 'project', $Project->getName(), null, 'project id : '.$idProject, null, $ip);
                    $success = "Le projet a bien été désarchivé.";
                } catch (\Throwable $th) {
                    $errors[] = "Une erreur innatendue est survenue.";
                    LogHistory::create($idOrganization, $idUser, "ERROR", $action, 'project', $Project->getName(), null, 'project id : '.$idProject, $th->getMessage(), $ip);
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
