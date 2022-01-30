<?php
//import all models
require_once "../../services/header.php";

$idOrganization = $_SESSION["idOrganization"] ?? false;
$idUser = $_SESSION['idUser'] ?? false;
$rights = $_SESSION["rights"] ?? false;

$action = GETPOST('action');
$idProject = intval(GETPOST('idProject'));
$name = GETPOST('name');
$type = GETPOST('type');
$description = GETPOST('description');
$envoi = GETPOST('envoi');

$success = false;
$errors = array();

if($rights == 'admin')
{
    $Project = new Project();

    $errors = array();
    $success = false;

    $tpl = "creationProjets.php";

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
                    $Project->create();
                    LogHistory::create($idUser, 'create', 'project', $name);
                    $success = "Le projet a été créé avec succès.";
                } catch (\Throwable $th) {
                    //throw $th;
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