<?php
//import all models
require_once "../../services/header.php";

$idOrganization = $_SESSION["idOrganization"] ?? false;
$rights = $_SESSION["rights"] ?? false;

if($rights === "admin")
{
    $tpl = "detailsProjet.php";

    $action = GETPOST('action');
    $idProject = GETPOST('idProject');
    $projectName = GETPOST('projectName');
    $description = GETPOST('description');
    $type = GETPOST('type');

    $teamName = GETPOST('teamName');
    $teamNameUpdate = GETPOST('teamNameUpdate');
    $teamId = GETPOST('teamId');
    $errors = GETPOST('errors');

    if($idProject)
    {
        $Organization = new Organization($idOrganization);
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
                foreach($Team->getMembers() as $Member)
                {
                    if(in_array($Member, $freeUsers))
                    {
                        $key = array_search($Member, $freeUsers);
                        unset($freeUsers[$key]);
                    }
                }
            }
        }

        if($action == 'addTeam' || $action == "updateTeam")
        {
            $addingUsersIds = array();
            foreach($freeUsers as $key => $user)
            {
                if(GETPOST('addingUser'.$key))
                {
                    $addingUsersIds[] = GETPOST('addingUser'.$key);
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
                try {
                    // changement de nom d'équipe
                    if($teamNameUpdate)
                    {
                        $Team->updateName($teamNameUpdate, $teamId);
                    }

                    // ajout des users dans la team
                    foreach($addingUsersIds as $idUser)
                    {
                        $BelongsTo->create($idUser, $teamId);
                    }

                    // suppression des users dans la team
                    foreach($CurrentProject->teams as $team)
                    {
                        if($team->rowid == $teamId)
                        {
                            foreach($team->members as $key => $member)
                            {
                                if(GETPOST('removingUser'.$key))
                                {
                                    $fk_user = GETPOST('removingUser'.$key);
                                    $BelongsTo->delete($fk_user, $team->rowid);
                                }
                            }
                        }
                    }

                    $success = "L'équipe a bien été modifiée.";
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
        $teamsIds = array();

        foreach($Project->getTeams() as $Team)
        {
            $teamsIds[] = $Team->getRowid();
        }

        ?>
        <script>
        const CONTROLLERS_URL = <?php echo json_encode(CONTROLLERS_URL); ?>;
        const projectId = <?php echo json_encode($Project->getRowid()); ?>;
        var teamIds = <?php echo json_encode($teamsIds); ?>;
        //var ProjectTeams = <?php echo json_encode($Project->getTeams()); ?>;
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
