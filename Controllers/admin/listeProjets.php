<?php
//import all models
require_once "../../services/header.php";
require "layouts/head.php";

$action = GETPOST('action');
$projectId = intval(htmlentities(GETPOST('projectId')));

$Organization = new Organization($idOrganization);

$tpl = "listeProjets.php";

$errors = array();

if($action == 'deleteProject')
{
    if(is_int($projectId)) 
    {
        try {
                $Project = new Project();
                foreach($Organization->getProjects() as $Obj)
                {
                    if($Obj->getRowid() === $projectId)
                    {
                        $Project->setRowid($projectId);
                        break;
                    }
                }
                $Project->delete();
                $success = "Le projet a bien été supprimmé.";
                LogHistory::create($idUser, 'delete', 'project', $Project->getName());
        } catch (\Throwable $th) {
            $errors[] = $th;
        }
    }
    else
    {
        $errors[] = "Une erreur est survenue.";
    }
}


require_once VIEWS_PATH."admin/".$tpl;
