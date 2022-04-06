<?php
//import all models
require_once "../../services/header.php";
require "layouts/head.php";

$action     = htmlentities(GETPOST('action'));
$firstname  = htmlentities($_POST['firstname'] ?? '');
$lastname   = htmlentities($_POST['lastname'] ?? '');
$email      = htmlentities($_POST['email'] ?? '');
$success    = GETPOST('success');
$errors     = GETPOST("errors");

$User = new User($idUser);

$BelongsToRepository = new BelongsToRepository();

$Projects = array();
$Teams    = array();

// get all related projects to the user
foreach($User->getBelongsTo() as $key => $BelongsTo)
{
    // $Team = new Team($BelongsTo->getFk_team());
    $Team = new Team();
    $Team->fetch($BelongsTo->getFk_team(), 0);

    $Project = new Project();
    $Project->fetch($Team->getFk_project(), 0);

    if($key == 0)
    {
        $Teams[$Project->getRowid()] = $Team;
    }

    $Projects[] = $Project;
}

$tpl = "dashboard.php";

$errors = !empty($errors) ? unserialize($errors) : array();

if($action == 'userUpdate')
{
    if(!empty($email)) 
    {
        if(strlen($email) > 0 && filter_var($email, FILTER_VALIDATE_EMAIL))
        {
            try {          
                $User->setFirstname($firstname);
                $User->setLastname($lastname);
                $User->setEmail($email);
                $User->update();
                LogHistory::create($idOrganization, $idUser, "INFO", 'update', 'user', $User->getLastname().' '.$User->getFirstname(), null, 'user id : '.$User->getRowid(), null, $ip);
                $success = "Vos informations ont bien été mises à jour.";
            } catch (\Throwable $th) {
                $errors[] = "Une error est survenue.";
                LogHistory::create($idOrganization, $idUser, "ERROR", 'update', 'user', $User->getLastname().' '.$User->getFirstname(), null, 'user id : '.$User->getRowid(), $th->getMessage(), $ip);
            }
        } 
        else 
        {
            $errors[] = "L'adresse email n'est pas valide.";
        }
    } 
    else 
    {
        $errors[] = "L'adresse email ne peut être vide.";
    }
}

if($action == 'accountDelete')
{
    try {
        $User->delete();
        LogHistory::create($idOrganization, $idUser, "WARNING", 'delete', 'user', $User->getLastname().' '.$User->getFirstname(), null, 'user id : '.$User->getRowid(), null, $ip);
        header("location:".CONTROLLERS_URL."visitor/signout.php");
        exit;
    } catch (\Throwable $th) {
        //throw $th;
        $errors[] = "Une erreur innatendue est survenue.";
        LogHistory::create($idOrganization, $idUser, "ERROR", 'delete', 'user', $User->getLastname().' '.$User->getFirstname(), null, 'user id : '.$User->getRowid(), $th->getMessage(), $ip);
    }
}

require_once VIEWS_PATH."member/".$tpl;

?>