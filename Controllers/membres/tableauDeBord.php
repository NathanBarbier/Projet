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
    
    // All associated teams with the user

    $Projects = array();

    foreach($User->getBelongsTo() as $BelongsTo)
    {
        $Team = new Team($BelongsTo->getFk_team());

        if($Team->getActive() == true)
        {
            if($Team->getProject()->getActive() == true)
            {
                $TasksCount = 0;
                foreach($Team->getMapColumns() as $mapColumn)
                {
                    $TasksCount += count($mapColumn->getTasks());
                }

                $Project = $Team->getProject();
                $Project->membersCount = count($Team->getMembers());
                $Project->tasksCount = $TasksCount;
                $Project->teamName = $Team->getName();
        
                $Projects[] = $Project;
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