<?php
//import all models
require_once "../../services/header.php";

$idOrganization = $_SESSION["idOrganization"] ?? false;
$rights = $_SESSION["rights"] ?? false;

if($rights === "admin")
{
    $tpl = "detailsProjet.php";

    $action = GETPOST('action');
    $idProject = intval(GETPOST('idProject'));
    $projectName = GETPOST('projectName');
    $description = GETPOST('description');
    $type = GETPOST('type');

    $teamName = GETPOST('teamName');
    $teamNameUpdate = GETPOST('teamNameUpdate');
    $teamId = intval(GETPOST('teamId'));

    $errors = GETPOST('errors');

    if($idProject)
    {
        $Organization = new Organization($idOrganization);
        //todo : get the project from $Organization->projects
        $Project = new Project($idProject);
        $User = new User();
        $Team = new Team($teamId);
        $BelongsTo = new BelongsTo();

        $success = false;

        if($errors)
        {
            $errors = unserialize($errors);
        }
        else
        {
            $errors = array();
        }

        // Retrieve users who can join a new team
        $freeUsers = $Organization->getUsers();
        $freeUsersIds = array();

        // remove admins
        foreach($freeUsers as $key => $User)
        {
            if($User->isAdmin())
            {
                unset($freeUsers[$key]);
            }
            else
            {
                $freeUsersIds[] = $User->getRowid();
            }
        }

        // Remove users belonging to a team
        foreach($Organization->getProjects() as $Project)
        {
            foreach($Project->getTeams() as $Team)
            {
                foreach($Team->getUsers() as $User)
                {
                    if(in_array($User, $freeUsers))
                    {
                        $key = array_search($User, $freeUsers);
                        unset($freeUsers[$key]);
                        $key = array_search($User->getRowid(), $freeUsersIds);
                        unset($freeUsersIds[$key]);
                    }
                    if(count($freeUsers) == 0) break;
                }
                if(count($freeUsers) == 0) break;
            }
            if(count($freeUsers) == 0) break;
        }

        if($action == 'addTeam' || $action == "updateTeam")
        {
            $addingUsersIds = array();
            foreach($freeUsers as $key => $user)
            {
                if(GETPOST('addingUser'.$key))
                {
                    $addingUsersIds[] = intval(GETPOST('addingUser'.$key));
                }
            }
        }

        // actions
        if($action == "archiveTeam")
        {
            if($teamId)
            {
                try {
                    $Team->setActive(0);
                    $Team->update();
                    $success = "Le tableau de l'équipe à bien été archivé.";
                } catch (\Throwable $th) {
                    //throw $th;
                    $errors[] = "Une erreur innatendue est survenue.";
                }
            }
            else
            {
                $errors[] = "Vous n'avez pas sélectionné d'équipe.";
            }
        }

        if($action == "openTeam")
        {
            if($teamId)
            {
                try {
                    $Team->setActive(1);
                    $Team->update();
                    $success = "Le tableau de l'équipe à bien été ré-ouvert.";
                } catch (\Throwable $th) {
                    $errors[] = "Une erreur innatendue est survenue.";
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
                $success = "Le projet à bien été ré-ouvert.";
            } catch (\Throwable $th) {
                $errors[] = "Une erreur innatendue est survenue.";
                //throw $th;
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

                    $success = "Les informations du projet ont bien été mises à jour.";
                } 
                catch (\Throwable $th) 
                {
                    echo $th->getMessage();
                    $errors[] = "Une erreur inattendue est survenue.";
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

                        $teamId = $Team->fetchMaxId()->rowid;
    
                        $Team->setRowid($teamId);

                        foreach($addingUsersIds as $idUser)
                        {
                            $UserToAdd = $freeUsers[array_search($idUser, array_column($freeUsers, 'rowid'))];
                            $Team->addUser($UserToAdd);

                            $BelongsTo->create($idUser, $teamId);
                        }

                        $Project->addTeam($Team);

                        $success = "L'équipe a bien été créée.";
                    } catch (\Throwable $th) {
                        //throw $th;
                        $errors[] = "Une erreur inattendue est survenue.";
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

                        $Project->addTeam($Team);

                        $success = "L'équipe a bien été créée.";
                    } catch (\Throwable $th) {
                        //throw $th;
                        $errors[] = "Une erreur inattendue est survenue.";
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
            if($teamId)
            {
                try {
                    $Team->delete($teamId);

                    $Project->removeTeam($teamId);

                    $success = "L'équipe a bien été supprimée.";
                } catch (\Throwable $th) {
                    //throw $th;
                    $errors[] = "Une erreur innatendue est survenue.";
                }
            }
            else
            {
                $errors[] = "Vous n'avez pas sélectionné d'équipe";
            }
        }

        if($action == "updateTeam")
        {
            if($teamId)
            {
                try 
                {
                    // changement de nom d'équipe
                    if($teamNameUpdate)
                    {
                        $Team->setName($teamNameUpdate);
                    }

                    // ajout des users dans la team
                    foreach($addingUsersIds as $userId)
                    {
                        $UserToAdd = false;
                        // get the user to add
                        foreach($Organization->getUsers() as $User)
                        {
                            if($User->getRowid() == $userId)
                            {
                                $BelongsTo->create($userId, $Team->getRowid());

                                $Team->addUser($User);

                                // removing user from freeUsers
                                unset($freeUsersIds[array_search($User->getRowid(), $freeUsersIds)]);
                                foreach($freeUsers as $key => $freeUser)
                                {
                                    if($freeUser->getRowid() == $User->getRowid())
                                    {
                                        unset($freeUsers[$key]);
                                        break;
                                    }
                                }
                                break;
                            }
                        }
                    }

                    // suppression des users dans la team
                    foreach($Team->getUsers() as $key => $User)
                    {
                        if(GETPOST('removingUser'.$key))
                        {
                            $fk_user = intval(GETPOST('removingUser'.$key)); 

                            $BelongsTo->delete($fk_user, $Team->getRowid());

                            $freeUsersIds[] = $User->getRowid();
                            $freeUsers[] = $User;

                            $Team->removeUser($User->getRowid());
                        }
                    }

                    // update project -> team object
                    $Project->removeTeam($Team->getRowid());
                    $Project->addTeam($Team);
                    
                    $Team->update();

                    $success = "L'équipe a bien été modifiée.";
                } 
                catch (\Throwable $th) 
                {
                    //throw $th;
                    $errors[] = "Une erreur est survenue.";
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

        // For JS
        $teamIds = array();

        foreach($Project->getTeams() as $Team)
        {
            $teamIds[] = $Team->getRowid();
        }

        ?>
        <script>
        const CONTROLLERS_URL = <?php echo json_encode(CONTROLLERS_URL); ?>;
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
        $errors[] = "Aucun projet n'a été sélectionné.";
    }

    require_once VIEWS_PATH."admin/".$tpl;
}
else
{
    header("location:".ROOT_URL."index.php");
}


?>
