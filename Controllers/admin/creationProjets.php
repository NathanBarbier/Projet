<?php
//import all models
require_once "../../traitements/header.php";

$idOrganization = $_SESSION["idOrganization"] ?? false;

$action = GETPOST('action');
$idProject = GETPOST('idProject');

$name = GETPOST('name');
$type = GETPOST('type');
$deadline = GETPOST('deadline');
$description = GETPOST('description');
$envoi = GETPOST('envoi');

$rights = $_SESSION["rights"] ?? false;

$success = false;
$errors = array();

if($rights == 'admin')
{
    $Team = new Team();
    $Project = new Project();

    $teams = $Team->fetchAll($idOrganization);

    $maxIdProject = $Project->fetchMaxId()->maxId;

    $errors = array();
    $success = false;

    $data = new stdClass;

    $tpl = "creationProjets.php";

    if($action == "addProjet")
    {
        if($envoi || $idProject)
        {
            if($name && $type)
            {
                $status = $Project->create($name, $type, $deadline, $description, $idOrganization);

                if($status)
                {
                    $success = "Le projet a été créé avec succès.";
                }
                else
                {
                    $errors[] = "Une erreur est survenue.";
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

} 
else 
{
    header('location:'.ROOT_PATH.'index.php');
}

?>