<?php
//import all models
require_once "../../services/header.php";
require "layouts/head.php";

$tpl = "detailsProjet.php";

$action = htmlentities(GETPOST('action'));
$idProject = htmlentities(intval(GETPOST('idProject')));
$projectName = htmlentities(GETPOST('projectName'));
$description = htmlentities(GETPOST('description'));
$type = htmlentities(GETPOST('type'));
$teamName = htmlentities(GETPOST('teamName'));
$teamNameUpdate = htmlentities(GETPOST('teamNameUpdate'));
$teamId = htmlentities(intval(GETPOST('teamId')));
$errors = GETPOST('errors');

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
    $Organization->fetchProjects(1);
    $Organization->fetchUsers();

    if(!empty($Organization) && $Organization->checkProject($idProject))
    {
        $User = new User();
        $Team = new Team($teamId);
        $BelongsTo = new BelongsTo();
        $Project = new Project();

        // get Project
        foreach($Organization->getProjects() as $Obj)
        {
            if($idProject == $Obj->getRowid())
            {
                $Project = $Obj;
            }
        }

        $success = false;

        // Retrieve users who can join a new team
        $freeUsers = $Organization->getUsers();
        $freeUsersIds = array();

        // remove admins
        foreach($freeUsers as $key => $TeamUser)
        {
            if($TeamUser->isAdmin())
            {
                unset($freeUsers[$key]);
            }
            else
            {
                $freeUsersIds[] = $TeamUser->getRowid();
            }
        }

        // Remove users belonging to the team
        foreach($Project->getTeams() as $TempTeam) {
            foreach($TempTeam->getUsers() as $TempUser) {
                $key = array_search($TempUser, $freeUsers);
                unset($freeUsers[$key]);
                $key = array_search($TempUser->getRowid(), $freeUsersIds);
                unset($freeUsersIds[$key]);
            }
        }

        if($action == 'addTeam' || $action == "updateTeam")
        {
            $addingUsersIds = array();
            $i = 0;
            foreach($freeUsers as $key => $user)
            {
                if(GETPOST('addingUser'.$i))
                {
                    $addingUsersIds[] = intval(GETPOST('addingUser'.$i));
                }
                $i++;
            }
        }

        // actions
        if($action == "archiveTeam")
        {
            if($teamId && $Project->checkTeam($teamId))
            {
                try {
                    $Team->setActive(0);
                    $Team->update();
                    LogHistory::create($idOrganization, $idUser, "WARNING", 'archive', 'team board', $Team->getName(), '', 'team id : '.$teamId);
                    $success = "Le tableau de l'équipe à bien été archivé.";
                } catch (\Throwable $th) {
                    $errors[] = "Une erreur innatendue est survenue.";
                    LogHistory::create($idOrganization, $idUser, "ERROR", 'archive', 'team board', $Team->getName(), '', 'team id : '.$teamId, $th);
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
                    LogHistory::create($idOrganization, $idUser, "WARNING", 'unarchive', 'team board', $Team->getName(), '', 'team id : '.$teamId);
                    $success = "Le tableau de l'équipe à bien été ré-ouvert.";
                } catch (\Throwable $th) {
                    $errors[] = "Une erreur innatendue est survenue.";
                    LogHistory::create($idOrganization, $idUser, "ERROR", 'unarchive', 'team board', $Team->getName(), '', 'team id : '.$teamId, $th);
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
                LogHistory::create($idOrganization, $idUser, "WARNING", 'unarchive', 'project', $Project->getName(), '', 'project id : '.$Project->getRowid());
                $success = "Le projet à bien été ré-ouvert.";
            } catch (\Throwable $th) {
                $errors[] = "Une erreur innatendue est survenue.";
                LogHistory::create($idOrganization, $idUser, "ERROR", 'unarchive', 'project', $Project->getName(), '', 'project id : '.$Project->getRowid(), $th);
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
                    LogHistory::create($idOrganization, $idUser, "INFO",'update', 'project', $Project->getName(), '', 'project id : '.$Project->getRowid());
                    $success = "Les informations du projet ont bien été mises à jour.";
                } 
                catch (\Throwable $th) 
                {
                    $errors[] = "Une erreur inattendue est survenue.";
                    LogHistory::create($idOrganization, $idUser, "ERROR",'update', 'project', $Project->getName(), '', 'project id : '.$Project->getRowid(), $th);
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
                        $Team->create();
                        LogHistory::create($idOrganization, $idUser, "INFO",'create', 'team', $Team->getName());

                        $teamId = $Team->fetchMaxId()->rowid;
    
                        $Team->setRowid($teamId);

                        $freeUsersToUnset = array();
                        // check if the users are free before adding them
                        foreach($addingUsersIds as $idUser)
                        {
                            foreach($freeUsers as $key => $freeUser)
                            {
                                if($freeUser->getRowid() == $idUser)
                                {
                                    $UserToAdd = $freeUser;
                                    $Team->addUser($UserToAdd);
                                    $freeUsersToUnset[] = $key;
                                    break;
                                }
                            }

                            $key = array_search($idUser, $freeUsersIds);
                            unset($freeUsersIds[$key]);

                            $BelongsTo->setFk_user($idUser);
                            $BelongsTo->setFk_team($teamId);

                            $BelongsTo->create();
                        }

                        foreach($freeUsersToUnset as $key)
                        {
                            unset($freeUsers[$key]);
                        }

                        $Project->addTeam($Team);

                        $success = "L'équipe a bien été créée.";
                    } catch (\Throwable $th) {
                        $errors[] = "Une erreur inattendue est survenue.";
                        LogHistory::create($idOrganization, $idUser, "ERROR",'create', 'team', $Team->getName(), '', '', $th);
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
                        $Team->create();

                        $teamId = $Team->fetchMaxId()->rowid;
                        $Team->setRowid($teamId);
                        LogHistory::create($idOrganization, $idUser, "INFO",'create', 'team', $Team->getName());

                        $Project->addTeam($Team);

                        $success = "L'équipe a bien été créée.";
                    } catch (\Throwable $th) {
                        $errors[] = "Une erreur inattendue est survenue.";
                        LogHistory::create($idOrganization, $idUser, "ERROR",'create', 'team', $Team->getName(), '', '', $th);
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
                    LogHistory::create($idOrganization, $idUser, "IMPORTANT",'delete', 'team', $Team->getName());
                    $Project->removeTeam($teamId);

                    // get team users to free them
                    foreach($Team->getUsers() as $TeamUser)
                    {
                        $freeUsers[] = $TeamUser;
                        $freeUsersIds[] = $TeamUser->getRowid();
                    }

                    $success = "L'équipe a bien été supprimée.";
                } catch (\Throwable $th) {
                    $errors[] = "Une erreur innatendue est survenue.";
                    LogHistory::create($idOrganization, $idUser, "ERROR",'delete', 'team', $Team->getName(), '', 'team id : '.$teamId, $th);
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

                    $freeUsersToUnset = array();
                    // check if the users are free before adding them
                    foreach($addingUsersIds as $idUser)
                    {
                        foreach($freeUsers as $key => $freeUser)
                        {
                            if($freeUser->getRowid() == $idUser)
                            {
                                $BelongsTo->setFk_user($idUser);
                                $BelongsTo->setFk_team($Team->getRowid());
                                $BelongsTo->create();
                                
                                $Team->addUser($freeUser);
                                $freeUsersToUnset[] = $key;
                                break;
                            }
                        }

                        $key = array_search($idUser, $freeUsersIds);
                        unset($freeUsersIds[$key]);

                        $BelongsTo->setFk_user($idUser);
                        $BelongsTo->setFk_team($teamId);

                        $BelongsTo->create();
                    }

                    foreach($freeUsersToUnset as $key)
                    {
                        unset($freeUsers[$key]);
                    }

                    // remove team users
                    foreach($Team->getUsers() as $key => $TeamUser)
                    {
                        if(GETPOST('removingUser'.$key))
                        {
                            $fk_user = intval(GETPOST('removingUser'.$key)); 

                            $BelongsTo = new BelongsTo();
                            
                            $BelongsTo->setFk_user($fk_user);
                            $BelongsTo->setFk_team($team->getRowid());

                            $BelongsTo->delete();

                            $freeUsersIds[] = $TeamUser->getRowid();
                            $freeUsers[] = $TeamUser;

                            $Team->removeUser($TeamUser->getRowid());
                        }
                    }

                    // update project -> team object
                    $Project->removeTeam($Team->getRowid());
                    $Project->addTeam($Team);
                    
                    $Team->update();
                    LogHistory::create($idOrganization, $idUser, "INFO",'update', 'team', $Team->getName(), '', 'team id : '.$teamId);
                    $success = "L'équipe a bien été modifiée.";
                } 
                catch (\Throwable $th) 
                {
                    $errors[] = "Une erreur est survenue.";
                    LogHistory::create($idOrganization, $idUser, "ERROR",'update', 'team', $Team->getName(), '', 'team id : '.$teamId, $th);
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
                    LogHistory::create($idOrganization, $idUser, "WARNING", $action, 'project', $Project->getName(), '', 'project id : '.$idProject);
                    $success = 'Le projet a bien été archivé.';
                } catch (\Throwable $th) {
                    $errors[] = "Une erreur innatendue est survenue.";
                    LogHistory::create($idOrganization, $idUser, "ERROR", $action, 'project', $Project->getName(), '', 'project id : '.$idProject, $th);
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
                    LogHistory::create($idOrganization, $idUser, "WARNING", $action, 'project', $Project->getName(), '', 'project id : '.$idProject);
                    $success = "Le projet a bien été désarchivé.";
                } catch (\Throwable $th) {
                    $errors[] = "Une erreur innatendue est survenue.";
                    LogHistory::create($idOrganization, $idUser, "ERROR", $action, 'project', $Project->getName(), '', 'project id : '.$idProject, $th);
                }
            }
            else
            {
                $errors[] = "Aucun projet n'a été sélectionné.";
            }
        }

        // For JS
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

require_once VIEWS_PATH."admin/".$tpl;
?>
