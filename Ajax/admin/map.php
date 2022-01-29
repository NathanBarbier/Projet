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
                } catch (\Throwable $th) {
                    echo json_encode($th);
                }
            }
            break;
        case 'renameColumn':
            if($columnId && $columnName)
            {
                try {
                    $MapColumn->setName($columnName);
                    $MapColumn->update();
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
    
                    echo json_encode($Task);
                } catch (\Throwable $th) {
                    echo json_encode($th);
                }
            }
            break;
        case 'updateTask':
            try {
                $Task->setName($taskName);
                $Task->update();
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
                } catch (\Throwable $th) {
                    echo json_encode($th);
                }
            }
            break;
        case 'updateColumn':
            if($columnId && $columnName)
            {
                try {                    
                    $MapColumn->setName($columnName);
                    $MapColumn->update();
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
                    // $Task->fetchMembers();
                    $ids = array();

                    $Task = $Task->object_to_array($Task);
    
                    // $TaskMembers = $Task->getMembers();
                    
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