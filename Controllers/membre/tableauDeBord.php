<?php
//import all models
require_once "../../services/header.php";
require "layouts/head.php";

$action = htmlentities(GETPOST('action'));
$firstname = htmlentities(GETPOST('firstname'));
$lastname = htmlentities(GETPOST('lastname'));
$email = htmlentities(GETPOST('email'));
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
                LogHistory::create($idOrganization, $idUser, "INFO", 'update', 'user', $User->getLastname().' '.$User->getFirstname(), '', 'user id : '.$User->getRowid());
                $success = "Vos informations ont bien été mises à jour.";
            } catch (\Throwable $th) {
                $errors[] = "Une error est survenue.";
                LogHistory::create($idOrganization, $idUser, "ERROR", 'update', 'user', $User->getLastname().' '.$User->getFirstname(), '', 'user id : '.$User->getRowid(), $th);
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
    try {
        $User->delete();
        LogHistory::create($idOrganization, $idUser, "WARNING", 'delete', 'user', $User->getLastname().' '.$User->getFirstname(), '', 'user id : '.$User->getRowid());
        header("location:".CONTROLLERS_URL."visiteur/Deconnexion.php");
        exit;
    } catch (\Throwable $th) {
        //throw $th;
        $errors[] = "Une erreur innatendue est survenue.";
        LogHistory::create($idOrganization, $idUser, "ERROR", 'delete', 'user', $User->getLastname().' '.$User->getFirstname(), '', 'user id : '.$User->getRowid(), $th);
    }
}

require_once VIEWS_PATH."membre".DIRECTORY_SEPARATOR.$tpl;

?>