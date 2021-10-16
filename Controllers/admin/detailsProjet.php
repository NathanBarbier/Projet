<?php
//import all models
require_once "../../traitements/header.php";

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
    

    $Project = new Project($idProject);

    $CurrentProject = new stdClass;

    $CurrentProject->rowid = $idProject;
    $CurrentProject->name = $Project->getName();
    $CurrentProject->description = $Project->getDescritpion();
    $CurrentProject->type = $Project->getType();

    $success = false;
    $errors = array();

    $User = new User();
    $Team = new Team();
    $BelongsTo = new BelongsTo();

    $projectFreeUsers = $User->fetchFreeUsersByProjectId($idProject);

    $projectFreeUsersIds = array();
    foreach($projectFreeUsers as $user)
    {
        $projectFreeUsersIds[] = $user->rowid;
    }

    $projectTeamsIds = array();
    
    $lines = $Team->fetchByProjectId($idProject);

    foreach($lines as $line)
    {
        $projectTeamsIds[] = $line->rowid;
    }

    $CurrentProject->teams = $Team->fetchByTeamIds($projectTeamsIds);

    foreach($CurrentProject->teams as $key => $team)
    {
        $CurrentProject->teams[$key]->members = $User->fetchByTeam($team->rowid);
    }

    $addingUsersIds = array();
    foreach($projectFreeUsers as $key => $user)
    {
        if(GETPOST('addingUser'.$key))
        {
            $addingUsersIds[] = GETPOST('addingUser'.$key);
        }
    }

    if($action == "updateProject")
    {
        if($projectName && $description && $type)
        {
            $status = array();

            $status[] = $Project->updateName($projectName, $idProject);
            $status[] = $Project->updateDescription($description, $idProject);
            $status[] = $Project->updateType($type, $idProject);

            if(in_array(false, $status))
            {
                $errors[] = "Une erreur inattendue est survenue.";
            }
            else
            {
                $success = "Les informations du projet ont bien été mises à jour.";
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
                $status = array();
                // create team with users
                $status[] = $Team->create($teamName, $idOrganization, $idProject);

                $idTeam = $Team->fetchMaxId()->rowid;

                foreach($addingUsersIds as $idUser)
                {
                    $status[] = $BelongsTo->create($idUser, $idTeam);
                }

                if(!in_array(false, $status))
                {
                    $success = "L'équipe a bien été créée.";
                }
                else
                {
                    $errors[] = "Une erreur inattendue est survenue.";
                }
            }
            else
            {
                // create team without users
                $status = $Team->create($teamName, $idOrganization, $idProject);

                if($status)
                {
                    $success = "L'équipe a bien été créée.";
                }
                else
                {
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
            $status = $Team->delete($teamId);

            if($status)
            {
                $success = "L'équipe a bien été supprimée.";
            }
            else
            {
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
            $status = array();

            // changement de nom d'équipe
            if($teamNameUpdate)
            {
                $status[] = $Team->updateName($teamNameUpdate, $teamId);
            }

            // ajout des users dans la team
            foreach($addingUsersIds as $idUser)
            {
                $status[] = $BelongsTo->create($idUser, $teamId);
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
                            $status[] = $BelongsTo->delete($fk_user, $team->rowid);
                        }
                    }
                }
            }

            // exit;

            if(!in_array(false, $status))
            {
                $success = "L'équipe a bien été modifiée.";
            }
            else
            {
                $errors[] = "Une erreur innatendue est survenue.";
            }
        }
        else
        {
            $errors[] = "Vous n'avez pas sélectionné d'équipe";
        }

    }


    if($success)
    {
        $Project = new Project($idProject);

        $CurrentProject = new stdClass;

        $CurrentProject->rowid = $idProject;
        $CurrentProject->name = $Project->getName();
        $CurrentProject->description = $Project->getDescritpion();
        $CurrentProject->type = $Project->getType();

        $projectFreeUsers = $User->fetchFreeUsersByProjectId($idProject);

        $projectFreeUsersIds = array();
        foreach($projectFreeUsers as $user)
        {
            $projectFreeUsersIds[] = $user->rowid;
        }
    
        $projectTeamsIds = array();
        
        $lines = $Team->fetchByProjectId($idProject);
        foreach($lines as $line)
        {
            $projectTeamsIds[] = $line->rowid;
        }
    
        $CurrentProject->teams = $Team->fetchByTeamIds($projectTeamsIds);

        foreach($CurrentProject->teams as $key => $team)
        {
            $CurrentProject->teams[$key]->members = $User->fetchByTeam($team->rowid);
        }
    }


    require_once VIEWS_PATH."admin/".$tpl;
}
else
{
    header("location:".ROOT_URL."index.php");
}


?>
