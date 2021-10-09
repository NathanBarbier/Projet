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
    $TaskComment = new TaskComment();

    $action = GETPOST('action');
    $teamId = GETPOST('teamId');
    $columnName = GETPOST('columnName');
    $columnId = GETPOST('columnId');
    $taskName = GETPOST('taskName');
    $taskId = GETPOST('taskId');
    $taskNote = GETPOST('taskNote');
    $commentId = GETPOST('commentId');

    switch($action)
    {
        case 'updateTaskNote':
            if($commentId && $taskNote)
            {
                $status = $TaskComment->updateNote($taskNote, $commentId);
            }               
            break;
        case 'deleteTaskNote':
            if($commentId)
            {
                $status = $TaskComment->delete($commentId);
            }
            break;
        case 'addTaskNote':
            if($taskId && $idUser)
            {
                $commentId = $TaskComment->create($taskId, $idUser);
                echo json_encode($commentId);
            }
            break;
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
        case 'taskColumnUpdate':
            $status = $Task->updateFk_column($columnId, $taskId);
            break;
        case 'upTask':
            if($columnId && $taskId)
            {
                $status = $Task->switchRank($taskId, $columnId, 'up');   
            }
            break;
        case 'downTask':
            if($columnId && $taskId)
            {
                $status = $Task->switchRank($taskId, $columnId, 'down');   
            }
            break;
        case 'getTaskComments':
            if($taskId) 
            {
                $comments = $TaskComment->fetchAll($taskId);
                echo json_encode($comments);
            }
            break;
    }

}
else
{
    header("location:".ROOT_URL."index.php");
}
?>