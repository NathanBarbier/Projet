<?php 
// import all models
require_once "../../traitements/header.php";

$idUser = $_SESSION["idUser"] ?? false;
$rights = $_SESSION["rights"] ?? false;
$idOrganization = $_SESSION["idOrganization"] ?? null;

if($rights == 'user')
{
    $Organization = new Organization($idOrganization);
    $MapColumns = new MapColumns();
    $Task = new Task();

    $action = GETPOST('action');
    $teamId = GETPOST('teamId');

    if($action == 'getLastColumnId')
    {
        $columnId = $MapColumns->fetch_last_insert_id()->rowid;
        echo json_encode($columnId);
        exit;
    }

    if($action == 'getLastTaskId')
    {
        $taskId = $Task->fetch_last_insert_id()->rowid;
        echo json_encode($taskId);
        exit;
    }

}
else
{
    header("location:".ROOT_URL."index.php");
}
?>