<?php
//import all models
require_once "../../services/header.php";
require "layouts/head.php";

$action = GETPOST('action');
$projectId = intval(htmlentities(GETPOST('projectId')));

$Organization = new Organization();
$Organization->setRowid($idOrganization);
$Organization->fetchProjects(0);

$tpl = "listeProjets.php";

$errors = array();
$success = false;

// for pagination
$offset = 10;

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

?>
<script>
var offset = <?php echo json_encode($offset); ?>;
</script>
<?php

require_once VIEWS_PATH."admin/".$tpl;
