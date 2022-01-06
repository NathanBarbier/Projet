<?php
//import all models
require_once "../../services/header.php";

$idUser = $_SESSION["idUser"] ?? null;
$rights = $_SESSION["rights"] ?? false;
$idOrganization = $_SESSION["idOrganization"] ?? false;

if($rights === "user")
{    
    $action = GETPOST('action');
    $firstname = GETPOST('firstname');
    $lastname = GETPOST('lastname');
    $email = GETPOST('email');
    $success = GETPOST('success');
    $errors = GETPOST("errors");

    $User = new User($idUser);
    
    $BelongsTo = new BelongsTo();
    // All associated teams with the user
    $BelongsToAll = $BelongsTo->fetchAll($idUser);

    $userProjects = array();

    foreach($BelongsToAll as $BelongsTo)
    {
        $teamId = $BelongsTo->getFk_team();
        $Team = new Team($teamId);

        if($Team->getActive() == true)
        {
            // on récupère l'id du projet lié à cette équipe
            $projectId = $Team->getIdProject();
    
            $Project = new Project($projectId);
    
            if($Project->getActive() == true)
            {
                $Tasks = new Task($projectId);
        
                $mapColumns = $Team->getMapColumns();
                $TasksCount = 0;
        
                foreach($mapColumns as $mapColumn)
                {
                    $TasksCount += count($mapColumn->getTasks());
                }
        
                // On affecte pour chaque projets sur lequel travaille l'user
                $ProjectInfo = new stdClass;
                $ProjectInfo->membersCount = $Project->fetch_members_count();
                $ProjectInfo->tasksCount = $TasksCount;
                $ProjectInfo->projectName = $Project->getName();
                $ProjectInfo->teamName = $Team->getName();
                $ProjectInfo->rowid = $projectId;
        
                $userProjects[$projectId] = $ProjectInfo;
            }
        }
    }
    
    $tpl = "tableauDeBord.php";
    
    if($errors)
    {
        $errors = unserialize($errors);
    }
    else
    {
        $errors = array();
    }
    
    if($action == 'userUpdate')
    {
        if($firstname && $lastname && $email)
        {
            if(filter_var($email, FILTER_VALIDATE_EMAIL))
            {
                $status = $User->updateInformations($firstname, $lastname, $email);

                if($status)
                {
                    $success = "Vos informations ont bien été mises à jour.";
                }
                else
                {
                    $errors[] = "Une error est survenue.";
                }
            }
            else
            {
                $errors[] = "L'adresse email n'est pas valide.";
            }
        }
    }

    if($action == 'accountDelete')
    {
        if($idUser)
        {
            $status = $User->delete($idUser);

            if($status)
            {
                header("location:".CONTROLLERS_URL."general/Deconnexion.php");
                exit;
            }
            else
            {
                $errors[] = "Une erreur innatendue est survenue.";
            }
        }
    }


    if($success)
    {
        $User = new User($idUser);
        $Team = new Team();
        $Project = new Project($Team->getidProject());
    }

    $CurrentUser = new stdClass;
    $CurrentUser->firstname = $User->getFirstname();
    $CurrentUser->lastname = $User->getLastname();
    $CurrentUser->email = $User->getEmail();
    
    require_once VIEWS_PATH."membres".DIRECTORY_SEPARATOR.$tpl;
}
else
{
    header("location:".ROOT_URL."index.php");
}

?>