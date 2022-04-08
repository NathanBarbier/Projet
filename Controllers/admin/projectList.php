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
$page = CONTROLLERS_URL."admin/".$tpl;

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

        try 
        {
            // delete in db
            $Project->delete();

            // for notification
            $success = "Le projet a bien été supprimmé.";
            
            // log entry
            LogHistory::create($idUser, 'delete', 'project', $projectId, null, null, $idOrganization, "IMPORTANT", null, $ip, $page);
            
            // remove from Organization -> projects List
            $Organization->removeProject($Project->getRowid());
        } 
        catch (\Throwable $th) 
        {
            $errors[] = $th;
            
            // log entry
            LogHistory::create($idUser, 'delete', 'project', $projectId, null, null, $idOrganization, "ERROR", $th->getMessage(), $ip, $page);
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
