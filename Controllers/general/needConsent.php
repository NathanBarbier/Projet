<?php 
//import all models
require_once "../../services/header.php";

$rights = $_SESSION["rights"] ?? false;
$idUser = $_SESSION["idUser"] ?? false;

if($rights === 'needConsent' && $idUser)
{
    $action = GETPOST('action');
    
    $User = new User($idUser);
    $Organization = new Organization();
    
    $errors = array();
    $success = false;
    
    $data = array();
    
    $tpl = "needConsent.php";

    if($action == "refuseConsent")
    {
        // DELETE ACCOUNT
        $status = $User->delete();
        header('location:'.CONTROLLERS_URL.'general/deconnexion.php');
        exit;
    }

    if($action == "giveConsent")
    {
        $consent = true;
        $status = $User->updateConsent($consent);

        if($status)
        {
            $_SESSION["rights"] = $User->getAdmin() ? 'admin' : 'user';
            header('location:'.ROOT_URL.'index.php');
            exit;
        }
        else
        {
            $errors[] = "Une erreur innatendue est survenue.";
        }
    }

    require_once VIEWS_URL.'general/'.$tpl;

}
else
{
    header('location:'.ROOT_URL.'index.php');
}
?>