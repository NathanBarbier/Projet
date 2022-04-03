<?php
//import all models
require_once "../../services/header.php";
require "layouts/head.php";

$action = GETPOST('action');
$projectId = intval(htmlentities(GETPOST('projectId')));

$Organization = new Organization();
$Organization->setRowid($idOrganization);
$Organization->fetchProjects(0);

$tpl = "projectList.php";

$errors = array();
$success = false;

// for pagination
$offset = 10;

if($action == 'deleteProject')
{
    if(is_int($projectId)) 
    {
        $Project = new Project();
        $Project->setRowid($projectId);
        $Project->fetchName();

        foreach($Organization->getProjects() as $Obj)
        {
            if($Obj->getRowid() === $projectId)
            {
                $Project->setRowid($projectId);
                break;
            }
        }

        try 
        {
            // delete in db
            $Project->delete();

            // for notification
            $success = "Le projet a bien été supprimmé.";
            
            // log entry
            LogHistory::create($idOrganization, $idUser, "IMPORTANT", 'delete', 'project', $Project->getName() ?? '', null, 'project id : '.$Project->getRowid());
            
            // remove from Organization -> projects List
            $Organization->removeProject($Project->getRowid());
        } 
        catch (\Throwable $th) 
        {
            $errors[] = $th;
            
            // log entry
            LogHistory::create($idOrganization, $idUser, "ERROR", 'delete', 'project', $Project->getName() ?? '', null, 'project id : '.$Project->getRowid(), $th->getMessage());
        }
    }
    else
    {
        $errors[] = "Une erreur est survenue.";
    }
}

?>
<script>
var offset = <?php echo json_encode($offset); ?>;
</script>
<?php

require_once VIEWS_PATH."admin/".$tpl;
