<?php 
// import all models
require_once "../../services/header.php";

$rights = $_SESSION["rights"] ?? false;
$idOrganization = $_SESSION["idOrganization"] ?? null;
$idUser = $_SESSION['idUser'] ?? null;

if($rights == 'admin')
{
    $action = GETPOST('action');
    $teamId = intval(GETPOST('teamId'));
    $columnName = GETPOST('columnName');
    $columnId = intval(GETPOST('columnId'));
    $taskName = GETPOST('taskName');
    $taskId = intval(GETPOST('taskId'));
    $taskNote = GETPOST('taskNote');
    $commentId = intval(GETPOST('commentId'));
    $memberId = intval(GETPOST('memberId'));

    $MapColumn = new MapColumn($columnId);
    $Task = new Task($taskId);
    $TaskComment = new TaskComment($commentId);
    $TaskMember = new TaskMember($memberId);
    $User = new User();

    switch($action)
    {
        case 'updateTaskNote':
            if($commentId && $taskNote)
            {
                try {
                    $authorId = $TaskComment->getFk_user();
                    if($authorId == $idUser)
                    {
                        $TaskComment->setNote($taskNote);
                        $TaskComment->update();
                        LogHistory::create($idUser, 'update', 'task comment', $TaskComment->getNote());
                    }
                } catch (\Throwable $th) {
                    echo json_encode($th);
                }
            }               
            break;
        case 'addTaskNote':
            if($taskId)
            {
                try {
                    $TaskComment->setFk_task($taskId);
                    $TaskComment->setFk_user($idUser);
                    $TaskComment->setNote('');
    
                    $commentId = $TaskComment->create();
                    LogHistory::create($idUser, 'create', 'task comment', $TaskComment->getNote());
    
                    echo json_encode($commentId);
                } catch (\Throwable $th) {
                    echo json_encode($th);
                }
            }
            break;
        case 'attributeMemberToTask':
            if($taskId && $memberId)
            {
                try {
                    $TaskMember->setFk_user($memberId);
                    $TaskMember->setFk_task($taskId);
                    $TaskMember->create();
                    LogHistory::create($idUser, 'create', 'task member', '', 'user id : '.$memberId.' task id : '.$taskId);
                } catch (\Throwable $th) {
                    echo json_encode($th);
                }
            }
            break;
        case 'desattributeMemberToTask':
            if($taskId && $memberId)
            {
                try {
                    $TaskMember->setFk_user($memberId);
                    $TaskMember->setFk_task($taskId);
                    $TaskMember->delete();
                    LogHistory::create($idUser, 'delete', 'task member', '', 'user id : '.$memberId.' task id : '.$taskId);
                } catch (\Throwable $th) {
                    echo json_encode($th);
                }
            }
            break;
        case 'addColumn':
            if($teamId && $columnName)
            {
                try {
                    $MapColumn->setFk_team($teamId);
                    $MapColumn->setName($columnName);
                    $MapColumn->create();
                    LogHistory::create($idUser, 'create', 'column', $MapColumn->getName());
                } catch (\Throwable $th) {
                    echo json_encode($th);
                }
            }
            break;
        case 'renameColumn':
            if($columnId && $columnName)
            {
                try {
                    $oldName = $MapColumn->getName();
                    $MapColumn->setName($columnName);
                    $MapColumn->update();
                    LogHistory::create($idUser, 'update name', 'column', $oldName, $MapColumn->getName());
                } catch (\Throwable $th) {
                    echo json_encode($th);
                }
            }
            break;
        case 'addTask':
            if($columnId)
            {
                try {
                    $Task->setActive(1);
                    $Task->setFk_user($idUser);
                    $Task->setfk_column($columnId);
                    $Task->create();
                    $taskId = intval($Task->fetch_last_insert_id()) + 1;
                    LogHistory::create($idUser, 'create', 'task', 'task id : '.$taskId);
                    echo json_encode($Task);
                } catch (\Throwable $th) {
                    echo json_encode($th);
                }
            }
            break;
        case 'updateTask':
            try {
                $oldName = $Task->getName();
                $Task->setName($taskName);
                $Task->update();
                LogHistory::create($idUser, 'update', 'task', $oldName, $Task->getName());
            } catch (\Throwable $th) {
                echo json_encode($th);
            }
            break;
        case 'taskColumnUpdate':
            if($columnId && $taskId)
            {
                try {
                    $Task->setFk_column($columnId);
                    $Task->update();
                    LogHistory::create($idUser, 'move', 'task to column', $Task->getName(), 'column id : '.$columnId);
                } catch (\Throwable $th) {
                    echo json_encode($th);
                }
            }
            break;
        case 'upTask':
            if($columnId && $taskId)
            {
                try {
                    $Task->switchRank($taskId, $columnId, 'up'); 
                    LogHistory::create($idUser, 'up task', 'task', $Task->getName());
                } catch (\Throwable $th) {
                    echo json_encode($th);
                }
            }
            break;
        case 'downTask':
            if($columnId && $taskId)
            {
                try {
                    $Task->switchRank($taskId, $columnId, 'down');
                    LogHistory::create($idUser, 'down task', 'task', $Task->getName());
                } catch (\Throwable $th) {
                    echo json_encode($th);
                }
            }
            break;
        case 'leftColumn':
            if($teamId && $columnId)
            {
                try {
                    $MapColumn->switchRank($columnId, $teamId, 'left');
                    LogHistory::create($idUser, 'move to left', 'column', $MapColumn->getName());
                } catch (\Throwable $th) {
                    echo json_encode($th);
                }
            }
            break;
        case 'rightColumn':
            if($teamId && $columnId)
            {
                try {
                    $MapColumn->switchRank($columnId, $teamId, 'right');
                    LogHistory::create($idUser, 'move to right', 'column', $MapColumn->getName());
                } catch (\Throwable $th) {
                    echo json_encode($th);
                }
            }
            break;
        case 'updateColumn':
            if($columnId && $columnName)
            {
                try {                    
                    $oldName = $MapColumn->getName();
                    $MapColumn->setName($columnName);
                    $MapColumn->update();
                    LogHistory::create($idUser, 'update', 'column', $oldName,$MapColumn->getName());
                } catch (\Throwable $th) {
                    echo json_encode($th);
                }
            }
            break;
        case 'deleteTaskNote':
            if($commentId)
            {
                try {
                    $TaskComment->delete();
                    LogHistory::create($idUser, 'delete', 'task comment', $TaskComment->getNote());
                } catch (\Throwable $th) {
                    echo json_encode($th);
                }
            }
            break;
        case 'deleteColumn':
            if($columnId)
            {
                try {
                    $MapColumn->delete();
                    LogHistory::create($idUser, 'delete', 'column', $MapColumn->getName());
                } catch (\Throwable $th) {
                    echo json_encode($th);
                }
            }
            break;
        case 'deleteTask':
            if($taskId)
            {
                try {
                    $Task->delete();
                    LogHistory::create($idUser, 'delete', 'task', $Task->getName());
                } catch (\Throwable $th) {
                    echo json_encode($th);
                }
            }
            break;
        case 'archiveTask':
            if($taskId)
            {
                try {
                    $Task->setActive(0);
                    $Task->update();
                    LogHistory::create($idUser, 'archive', 'task', $Task->getName());
                } catch (\Throwable $th) {
                    echo json_encode($th);
                }
            }
            break;
        case 'getLastColumnId':
            try {
                echo json_encode($MapColumn->fetch_last_insert_id());
            } catch (\Throwable $th) {
                echo json_encode($th);
            }
            break;
        case 'getLastTaskId':
            try {
                echo json_encode($Task->fetch_last_insert_id());
            } catch (\Throwable $th) {
                echo json_encode($th);
            }
            break;
        case 'getTaskComments':
            if($taskId) 
            {
                try {                    
                    $Organization = new Organization($idOrganization);
                    $Comments = $Task->getComments();
    
                    foreach($Comments as $key => $Comment)
                    {
                        $User = new User($Comment->getFk_user());
                        if($User->isAdmin())
                        {
                            $Comments[$key]->author = $Organization->getName();
                            $Comments[$key]->admin = true;
                        }
                        else
                        {
                            $Comments[$key]->author = $User->getLastname() . " " . $User->getFirstname();
                            $Comments[$key]->admin = false;
                        }

                        if($Comment->getFk_user() == $idUser)
                        {
                            $Comments[$key]->isAuthor = true;
                        }
                        else
                        {
                            $Comments[$key]->isAuthor = false;
                        }
                    }

                    $Comments = $Task->object_to_array($Task);
    
                    echo json_encode($Comments);
                } catch (\Throwable $th) {
                    echo json_encode($th);
                }
            }
            break;
        case 'getTaskMembers':
            if($taskId)
            {
                try {                    
                    // get users related to this task
                    $Task = $Task->object_to_array($Task);
                    echo json_encode($Task);
                } catch (\Throwable $th) {
                    echo json_encode($th);
                }
            }
            break;
    }

}
else
{
    header("location:".ROOT_URL."index.php");
}
?>