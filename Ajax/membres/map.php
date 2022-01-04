<?php 
// import all models
require_once "../../services/header.php";

$idUser = $_SESSION["idUser"] ?? false;
$rights = $_SESSION["rights"] ?? false;
$idOrganization = $_SESSION["idOrganization"] ?? null;

if($rights == 'user')
{
    $MapColumn = new MapColumn();
    $Task = new Task();
    $TaskComment = new TaskComment();
    $TaskMember = new TaskMember();
    $User = new User();

    $action = GETPOST('action');
    $teamId = GETPOST('teamId');
    $columnName = GETPOST('columnName');
    $columnId = GETPOST('columnId');
    $taskName = GETPOST('taskName');
    $taskId = GETPOST('taskId');
    $taskNote = GETPOST('taskNote');
    $commentId = GETPOST('commentId');
    $memberId = GETPOST('memberId');

    switch($action)
    {
        case 'updateTaskNote':
            if($commentId && $taskNote)
            {
                $authorId = $TaskComment->fetch($commentId)->fk_user;
                if($authorId == $idUser)
                {
                    $status = $TaskComment->updateNote($taskNote, $commentId);
                }
            }               
            break;
        case 'addTaskNote':
            if($taskId && $idUser)
            {
                $commentId = $TaskComment->create($taskId, $idUser);
                echo json_encode($commentId);
            }
            break;
        case 'attributeMemberToTask':
            if($taskId && $memberId)
            {
                $TaskMember->create($memberId, $taskId);
            }
            break;
        case 'desattributeMemberToTask':
            if($taskId && $memberId)
            {
                $TaskMember->deleteByTaskIdAndUserId($taskId, $memberId);
            }
            break;
        case 'addColumn':
            if($teamId && $columnName) $status = $MapColumn->create($columnName, $teamId);
            break;
        case 'renameColumn':
            if($columnId && $columnName)
            break;
        case 'addTask':
            if($columnId) $status = $Task->create($columnId, $idUser, 0);
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
        case 'leftColumn':
            if($teamId && $columnId)
            {
                $status = $MapColumn->switchRank($columnId, $teamId, 'left');
            }
            break;
        case 'rightColumn':
            if($teamId && $columnId)
            {
                $status = $MapColumn->switchRank($columnId, $teamId, 'right');
            }
            break;
        case 'updateColumn':
            if($columnId && $columnName)
            {
                $status = $MapColumn->updateName($columnName, $columnId);
            }
            break;
        case 'deleteTaskNote':
            if($commentId)
            {
                // check if is author
                // fetch comment author id
                $authorId = $TaskComment->fetch($commentId)->fk_user;
                if($authorId == $idUser)
                {
                    $TaskComment->delete($commentId);
                }
            }
            break;
        case 'deleteColumn':
            if($columnId)
            {
                $TaskComment->deleteByColumnId($columnId);
                $Task->deleteByColumnId($columnId);
                $MapColumn->delete($columnId);
            }
            break;
        case 'deleteTask':
            if($taskId)
            {
                $TaskComment->deleteByTaskId($taskId);
                $Task->delete($taskId);
            }
            break;
        case 'archiveTask':
            if($taskId)
            {
                $Task->updateActive(0, $taskId);
            }
            break;
        case 'getLastColumnId':
            $columnId = $MapColumn->fetch_last_insert_id()->rowid;
            echo json_encode($columnId);
            break;
        case 'getLastTaskId':
            $taskId = $Task->fetch_last_insert_id()->rowid;
            echo json_encode($taskId);
            break;
        case 'getTaskComments':
            if($taskId) 
            {
                $comments = $TaskComment->fetchAll($taskId);
                $Organization = new Organization();
                $author = $Organization->fetch($idOrganization)->name;

                foreach($comments as $key => $comment)
                {
                    if($comment->admin == true)
                    {
                        $comments[$key]->author = $author;
                    }
                }

                echo json_encode($comments);
            }
            break;
        case 'getTaskMembers':
            if($taskId)
            {
                $taskMembers = $TaskMembers->fetchAll($taskId);

                $membersIds = array();

                foreach($taskMembers as $member)
                {
                    $membersIds[] = $member->fk_user;
                }
                // get lastname and firstname
                
                $membersInfos = $User->fetchByIds($membersIds);
                

                echo json_encode($membersInfos);
            }
            break;
    }

}
else
{
    header("location:".ROOT_URL."index.php");
}
?>