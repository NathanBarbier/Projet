<?php 
// import all models
require_once "../../traitements/header.php";

$idUser = $_SESSION["idUser"] ?? false;
$rights = $_SESSION["rights"] ?? false;
$idOrganization = $_SESSION["idOrganization"] ?? null;

if($rights == 'user')
{
    // $Organization = new Organization($idOrganization);
    $MapColumns = new MapColumns();
    $Task = new Task();

    $action = GETPOST('action');
    $teamId = GETPOST('teamId');
    $columnName = GETPOST('columnName');
    $columnId = GETPOST('columnId');
    $taskName = GETPOST('taskName');
    $taskId = GETPOST('taskId');

    switch($action)
    {
        case 'getLastColumnId':
            $columnId = $MapColumns->fetch_last_insert_id()->rowid;
            echo json_encode($columnId);
            break;
        case 'getLastTaskId':
            $taskId = $Task->fetch_last_insert_id()->rowid;
            echo json_encode($taskId);
            break;
        case 'addColumn':
            if($teamId && $columnName) $status = $MapColumns->create($columnName, $teamId);
            break;
        case 'deleteColumn':
            if($columnId)
            {
                $status = array();
                $status[] = $Task->deleteByColumnId($columnId);
                $status[] = $MapColumns->delete($columnId);
            }
            break;
        case 'deleteTask':
            if($taskId) $status = $Task->delete($taskId);
            break;
        case 'renameColumn':
            if($columnId && $columnName)
            break;
        case 'addTask':
            if($columnId) $status = $Task->create($columnId);
            break;
        case 'updateTask':
            $status = $Task->updateName($taskName, $taskId);
            break;
    }

}
else
{
    header("location:".ROOT_URL."index.php");
}
?>