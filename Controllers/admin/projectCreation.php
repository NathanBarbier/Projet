<?php
//import all models
require_once "../../services/header.php";
require "layouts/head.php";

$action = htmlspecialchars(GETPOST('action'), ENT_NOQUOTES|ENT_SUBSTITUTE, "UTF-8");
$idProject = intval(GETPOST('idProject'), ENT_NOQUOTES|ENT_SUBSTITUTE, "UTF-8");
$name = htmlspecialchars(GETPOST('name'), ENT_NOQUOTES|ENT_SUBSTITUTE, "UTF-8");
$type = htmlspecialchars(GETPOST('type'), ENT_NOQUOTES|ENT_SUBSTITUTE, "UTF-8");
$description = htmlspecialchars(GETPOST('description'), ENT_NOQUOTES|ENT_SUBSTITUTE, "UTF-8");
$envoi = GETPOST('envoi');

$success = false;
$errors = array();

$Project = new Project();

$errors = array();
$success = false;

$tpl = "projectCreation.php";
$page = "controllers/admin/".$tpl;

if($action == "addProjet")
{
    if($envoi || $idProject)
    {
        if($name && $type)
        {
            try {
                $Project->setName($name);
                $Project->setType($type);
                $Project->setDescription($description);
                $Project->setFk_organization($idOrganization);
                $lastInsertedId = $Project->create();
                LogHistory::create($idUser, 'create', 'project', $lastInsertedId, $name, null, null, null, $idOrganization, "INFO", null, $ip, $page);
                $success = "Le projet a été créé avec succès.";
            } catch (\Throwable $th) {
                //throw $th;
                $errors[] = "Une erreur est survenue.";
                LogHistory::create($idUser, 'create', 'project', $lastInsertedId, $name, null, null, null, $idOrganization, "INFO", $th->getMessage(), $ip, $page);
            }
        } 
        else 
        {
            $errors[] = "Tous les champs ne sont pas remplis.";
        }
    } 
    else 
    {
        header('location:'.ROOT_PATH.'index.php');
    }
}

require_once VIEWS_PATH."admin/".$tpl;
?>