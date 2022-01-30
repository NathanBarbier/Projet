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
    // $Organization = new Organization($idOrganization);

    $Projects = array();
    // get all related projects to the user
    foreach($User->getBelongsTo() as $BelongsTo)
    {
        $Team = new Team($BelongsTo->getFk_team());
        $Projects[] = new Project($Team->getFk_project());
    }
    
    $tpl = "tableauDeBord.php";

    $errors = !empty($errors) ? unserialize($errors) : array();
    
    if($action == 'userUpdate')
    {
        if($firstname && $lastname && $email)
        {
            if(filter_var($email, FILTER_VALIDATE_EMAIL))
            {
                try {          
                    $User->setFirstname($firstname);
                    $User->setLastname($lastname);
                    $User->setEmail($email);
                    $User->update();
                    LogHistory::create($idUser, 'update', 'user', $User->getLastname().' '.$User->getFirstname());
                    $success = "Vos informations ont bien été mises à jour.";
                } catch (\Throwable $th) {
                    //throw $th;
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
            try {
                $User->delete();
                LogHistory::create($idUser, 'delete', 'user', $User->getLastname().' '.$User->getFirstname());
                header("location:".CONTROLLERS_URL."visiteur/Deconnexion.php");
                exit;
            } catch (\Throwable $th) {
                //throw $th;
                $errors[] = "Une erreur innatendue est survenue.";
            }
        }
    }
    
    require_once VIEWS_PATH."membre".DIRECTORY_SEPARATOR.$tpl;
}
else
{
    header("location:".ROOT_URL."index.php");
}

?>