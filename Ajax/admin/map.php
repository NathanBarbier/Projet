<?php 
// import all models
require_once "../../services/header.php";

$rights = $_SESSION["rights"] ?? false;
$idOrganization = $_SESSION["idOrganization"] ?? null;
$idUser = $_SESSION['idUser'] ?? null;

if($rights == 'admin')
{
    $action = GETPOST('action');
    $teamId = GETPOST('teamId');
    $columnName = GETPOST('columnName');
    $columnId = GETPOST('columnId');
    $taskName = GETPOST('taskName');
    $taskId = GETPOST('taskId');
    $taskNote = GETPOST('taskNote');
    $commentId = GETPOST('commentId');
    $memberId = GETPOST('memberId');

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
                $TaskComment->fetch($commentId);
                $authorId = $TaskComment->getRowid();
                if($authorId == $idOrganization)
                {
                    $status = $TaskComment->updateNote($taskNote, $commentId);
                }
            }               
            break;
        case 'addTaskNote':
            if($taskId)
            {
                $TaskComment->setFk_task($taskId);
                $TaskComment->setFk_user($idUser);
                $TaskComment->setNote('');

                $commentId = $TaskComment->create();

                echo json_encode($commentId);
            }
            break;
        case 'attributeMemberToTask':
            if($taskId && $memberId)
            {
                $TaskMember->setFk_user($memberId);
                $TaskMember->setFk_task($taskId);
                $TaskMember->create();
            }
            break;
        case 'desattributeMemberToTask':
            if($taskId && $memberId)
            {
                $TaskMember->setFk_user($memberId);
                $TaskMember->setFk_task($taskId);
                $TaskMember->delete();
            }
            break;
        case 'addColumn':
            if($teamId && $columnName)
            {
                $MapColumn->setFk_team($teamId);
                $MapColumn->setName($columnName);
                $MapColumn->create();
            }
            break;
        case 'renameColumn':
            if($columnId && $columnName)
            {
                $MapColumn->setName($columnName);
                $MapColumn->update();
            }
            break;
        case 'addTask':
            if($columnId)
            {
                $Task->setActive(1);
                $Task->setFk_user($idUser);
                $Task->setfk_column($columnId);
                $Task->create();

                echo json_encode($Task);
            }
            break;
        case 'updateTask':
            $Task->setName($taskName);
            $Task->update();
            break;
        case 'taskColumnUpdate':
            if($columnId && $taskId)
            {
                $Task->setFk_column($columnId);
                $Task->update();
            }
            break;
        case 'upTask':
            if($columnId && $taskId)
            {
                $Task->switchRank($taskId, $columnId, 'up'); 
            }
            break;
        case 'downTask':
            if($columnId && $taskId)
            {
                $Task->switchRank($taskId, $columnId, 'down');
            }
            break;
        case 'leftColumn':
            if($teamId && $columnId)
            {
                $MapColumn->switchRank($columnId, $teamId, 'left');
            }
            break;
        case 'rightColumn':
            if($teamId && $columnId)
            {
                $MapColumn->switchRank($columnId, $teamId, 'right');
            }
            break;
        case 'updateColumn':
            if($columnId && $columnName)
            {
                $MapColumn->setName($columnName);

                var_dump($MapColumn);

                $MapColumn->update();
                exit;
            }
            break;
        case 'deleteTaskNote':
            if($commentId)
            {
                $TaskComment->delete($commentId);
            }
            break;
        case 'deleteColumn':
            if($columnId)
            {
                $MapColumn->delete();
            }
            break;
        case 'deleteTask':
            if($taskId)
            {
                $Task->delete();
            }
            break;
        case 'archiveTask':
            if($taskId)
            {
                $Task->setActive(0);
                $Task->update();
            }
            break;
        case 'getLastColumnId':
            echo json_encode($MapColumn->fetch_last_insert_id());
            break;
        case 'getLastTaskId':
            echo json_encode($Task->fetch_last_insert_id());
            break;
        case 'getTaskComments':
            if($taskId) 
            {
                $Organization = new Organization($idOrganization);

                $Comments = $Task->getComments();

                // foreach($Comments as $key => $Comment)
                // {
                //     $User = new User($Comment->getFk_user());
                //     if($User->isAdmin())
                //     {
                //         $Comments[$key]->author = $Organization->getName();
                //     }
                // }

                echo json_encode($Comments);
            }
            break;
        case 'getTaskMembers':
            if($taskId)
            {
                // get users related to this task
                $Task->fetchMembers();
                $ids = array();

                foreach($Task->getMembers() as $TaskMember)
                {
                    $ids[] = $TaskMember->getFk_user();
                }

                $TaskMembers = $User->fetchByIds($ids);
                
                echo json_encode($TaskMembers);
            }
            break;
    }

}
else
{
    header("location:".ROOT_URL."index.php");
}
?>