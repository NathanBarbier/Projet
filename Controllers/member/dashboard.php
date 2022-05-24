<?php
//import all models
require_once "../../services/header.php";
require "layouts/head.php";

$action     = htmlspecialchars(GETPOST('action'), ENT_NOQUOTES|ENT_SUBSTITUTE, "UTF-8");
$firstname  = htmlspecialchars($_POST['firstname'] ?? '', ENT_NOQUOTES|ENT_SUBSTITUTE, "UTF-8");
$lastname   = htmlspecialchars($_POST['lastname'] ?? '', ENT_NOQUOTES|ENT_SUBSTITUTE, "UTF-8");
$email      = htmlspecialchars($_POST['email'] ?? '', ENT_NOQUOTES|ENT_SUBSTITUTE, "UTF-8");
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

    if($Project->isActive())
    {
        $Projects[] = $Project;
    }
}

$tpl = "dashboard.php";
$page = "controllers/member/".$tpl;

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
                LogHistory::create($idUser, 'update', 'user', $idUser, $firstname." ".$lastname, null, null, null, $idOrganization, "INFO", null, $ip, $page);
                $success = "Vos informations ont bien été mises à jour.";
            } catch (\Throwable $th) {
                $errors[] = "Une error est survenue.";
                LogHistory::create($idUser, 'update', 'user', $idUser, $firstname." ".$lastname, null, null, null, $idOrganization, "ERROR", $th->getMessage(), $ip, $page);
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
        LogHistory::create($idUser, 'delete', 'user', $idUser, $firstname." ".$lastname, null, null, null, $idOrganization, "WARNING", null, $ip, $page);
        header("location:".CONTROLLERS_URL."visitor/signout.php");
        exit;
    } catch (\Throwable $th) {
        //throw $th;
        $errors[] = "Une erreur innatendue est survenue.";
        LogHistory::create($idUser, 'delete', 'user', $idUser, $firstname." ".$lastname, null, null, null, $idOrganization, "ERROR", $th->getMessage(), $ip, $page);
    }
}

require_once VIEWS_PATH."member/".$tpl;

?>