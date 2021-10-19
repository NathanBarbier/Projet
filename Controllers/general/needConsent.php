<?php 
//import all models
require_once "../../traitements/header.php";

$rights = $_SESSION["rights"] ?? false;
$idUser = $_SESSION["idUser"] ?? false;

if($rights === 'needConsent')
{
    $action = GETPOST('action');
    
    $User = new User();
    $Organization = new Organization();
    
    $errors = array();
    $success = false;
    
    $data = array();
    
    $tpl = "needConsent.php";

    if($action == "refuseConsent")
    {
        // DELETE ACCOUNT
        $status = $User->delete($idUser);
        header('location:'.CONTROLLERS_URL.'general/deconnexion.php');
        exit;
    }

    if($action == "giveConsent")
    {
        $consent = true;
        $status = $User->updateConsent($consent, $idUser);

        if($status)
        {
            $_SESSION["rights"] = 'user';
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