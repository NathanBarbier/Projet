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
    $teamName = GETPOST('teamName');
    

    $Project = new Project($idProject);

    $CurrentProject = new stdClass;

    $CurrentProject->name = $Project->getName();
    $CurrentProject->description = $Project->getDescritpion();
    $CurrentProject->type = $Project->getType();

    // var_dump($CurrentProject);

    $success = false;
    $errors = array();

    //TODO récupérer tous les membres d'une organization
    //TODO qui ne sont pas attribué au projet
    // 
    $User = new User();
    $WorkTo = new WorkTo();
    $Team = new Team();
    $BelongsTo = new BelongsTo();

    $projectFreeUsers = $User->fetchFreeUsersByProjectId($idProject);


    // var_dump($projectFreeUsers);
    // exit;

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
                    $errors[] = "Une erreur innatendue est survenue.";
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
                    $errors[] = "Une erreur innatendue est survenue.";
                }
            }
        }
        else
        {
            $errors[] = "L'équipe n'a pas de nom.";
        }
    }

    // exit;

    require_once VIEWS_PATH."admin/".$tpl;
}
else
{
    header("location:".ROOT_URL."index.php");
}


?>
