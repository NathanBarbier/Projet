<?php 
//import all models
require_once "../../services/header.php";
require "layouts/head.php";

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
        header('location:'.CONTROLLERS_URL.'visitor/signout.php');
        exit;
    }

    if($action == "giveConsent")
    {
        try {
            $User->setConsentDate(date('Y-m-d H:i:s'));
            $User->setConsent(true);
            $User->update();
            $_SESSION["rights"] = $User->isAdmin() ? 'admin' : 'user';
            header('location:'.ROOT_URL.'index.php');
            exit;
        } catch (\Throwable $th) {
            //throw $th;
            $errors[] = "Une erreur innatendue est survenue.";
        }
    }

    require_once VIEWS_URL.'visitor/'.$tpl;

}
else
{
    header('location:'.ROOT_URL.'index.php');
}
?>