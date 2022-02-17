<?php
//import all models
require_once "../../services/header.php";
require "layouts/head.php";

$action = GETPOST('action');
$projectId = intval(htmlentities(GETPOST('projectId')));

$Organization = new Organization($idOrganization);

$tpl = "listeProjets.php";

$errors = array();
$success = false;
if($action == 'deleteProject')
{
    if(is_int($projectId)) 
    {
        $Project = new Project();
        foreach($Organization->getProjects() as $Obj)
        {
            if($Obj->getRowid() === $projectId)
            {
                $Project->setRowid($projectId);
                break;
            }
        }
        try {
                $Project->delete();
                $success = "Le projet a bien été supprimmé.";
                LogHistory::create($idOrganization, $idUser, "IMPORTANT", 'delete', 'project', $Project->getName(), '', 'project id : '.$Project->getRowid());
        } catch (\Throwable $th) {
            $errors[] = $th;
            LogHistory::create($idOrganization, $idUser, "ERROR", 'delete', 'project', $Project->getName(), '', 'project id : '.$Project->getRowid(), $th);
        }
    }
    else
    {
        $errors[] = "Une erreur est survenue.";
    }
}


require_once VIEWS_PATH."admin/".$tpl;
