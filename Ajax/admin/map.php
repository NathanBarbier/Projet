<?php 
// import all models
require_once "../../services/header.php";
// only allow access to ajax request
if( isset( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && ( $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' ) )
{
    $rights = $_SESSION["rights"] ?? false;
    $idOrganization = $_SESSION["idOrganization"] ?? null;
    $idUser = $_SESSION['idUser'] ?? null;
    
    if($rights == 'admin' && $idOrganization > 0 && $idUser > 0)
    {
        // security checks
        $projectId =intval( GETPOST('projectId'));
        $teamId = intval(GETPOST('teamId'));
    
        if($projectId > 0 && $teamId > 0)
        {
            $Organization = new Organization($idOrganization);
    
            // check if the project && team belong to the organization
            foreach($Organization->getProjects() as $project)
            {
                if($project->getRowid() == $projectId)
                {
                    $Project = $project;
                    foreach($project->getTeams() as $team)
                    {
                        if($team->getRowid() == $teamId)
                        {
                            $Team = $team;
                            break 2;
                        }
                    }
                }
            }
    
            if(!empty($Project) && !empty($Team))
            {
                $action = htmlentities(GETPOST('action'));
                $columnName = htmlentities(GETPOST('columnName'));
                $columnId = intval(GETPOST('columnId'));
                $taskName = htmlentities(GETPOST('taskName'));
                $taskId = intval(GETPOST('taskId'));
                $taskNote = htmlentities(GETPOST('taskNote'));
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
                        // check if the task comment belong to the team
                        if($commentId && $taskNote && $Team->checkTaskComment($commentId))
                        {
                            $authorId = $TaskComment->getFk_user();
                            if($authorId == $idUser)
                            {
                                try {
                                    $TaskComment->setNote($taskNote);
                                    $TaskComment->update();
                                    LogHistory::create($idUser, 'update', 'task comment', $TaskComment->getNote());
                                } catch (\Throwable $th) {
                                    // echo json_encode($th);
                                }
                            }           
                        }               
                        break;
                    case 'addTaskComment':
                        // check if the task belong to the team
                        if($taskId && $Team->checkTask($taskId))
                        {
                            try {
                                $TaskComment->setFk_task($taskId);
                                $TaskComment->setFk_user($idUser);
                                $TaskComment->setNote('');
                
                                $commentId = $TaskComment->create();
                                LogHistory::create($idUser, 'create', 'task comment', $TaskComment->getNote());
                                echo json_encode($commentId);
                            } catch (\Throwable $th) {
                                // echo json_encode($th);
                            }
                        }
                        break;
                    case 'attributeMemberToTask':
                        // check if the user & task belong to the current team
                        if($taskId && $memberId && $Team->checkUser($memberId) && $Team->checkTask($taskId))
                        {
                            try {
                                $TaskMember->setFk_user($memberId);
                                $TaskMember->setFk_task($taskId);
                                $TaskMember->create();
                                LogHistory::create($idUser, 'create', 'task member', '', 'user id : '.$memberId.' task id : '.$taskId);
                            } catch (\Throwable $th) {
                                // echo json_encode($th);
                            }
                        }
                        break;
                    case 'desattributeMemberToTask':
                        // check if the user and the task belong to the current team
                        if($taskId && $memberId && $Team->checkTask($taskId) && $Team->checkUser($memberId))
                        {
                            try {
                                $TaskMember->setFk_user($memberId);
                                $TaskMember->setFk_task($taskId);
                                $TaskMember->delete();
                                LogHistory::create($idUser, 'delete', 'task member', '', 'user id : '.$memberId.' task id : '.$taskId);
                            } catch (\Throwable $th) {
                                // echo json_encode($th);
                            }
                        }
                        break;
                    case 'addColumn':
                        if($columnName)
                        {
                            try {
                                $MapColumn->setFk_team($teamId);
                                $MapColumn->setName($columnName);
                                $MapColumn->create();
                                LogHistory::create($idUser, 'create', 'column', $MapColumn->getName());
                            } catch (\Throwable $th) {
                                // echo json_encode($th);
                            }
                        }
                        break;
                    case 'renameColumn':
                        // check if the column belong to the team
                        if($columnId && $columnName && $Team->checkColumn($columnId))
                        {
                            try {
                                $oldName = $MapColumn->getName();
                                $MapColumn->setName($columnName);
                                $MapColumn->update();
                                LogHistory::create($idUser, 'update name', 'column', $oldName, $MapColumn->getName());
                            } catch (\Throwable $th) {
                                // echo json_encode($th);
                            }
                        }
                        break;
                    case 'addTask':
                        // check if the column belong to the team
                        if($columnId && $Team->checkColumn($columnId))
                        {
                            try {
                                $Task->setActive(1);
                                $Task->setFk_user($idUser);
                                $Task->setfk_column($columnId);
                                $Task->create();
                                $taskId = intval($Task->fetch_last_insert_id());
                                LogHistory::create($idUser, 'create', 'task', 'task id : '.$taskId);
                                echo json_encode($Task);
                            } catch (\Throwable $th) {
                                // echo json_encode($th);
                            }
                        }
                        break;
                    case 'updateTask':
                        // check if the task belong to the team
                        if($taskId && $Team->checkTask($taskId))
                        {
                            try {
                                $oldName = $Task->getName();
                                $Task->setName($taskName);
                                $Task->update();
                                LogHistory::create($idUser, 'update', 'task', $oldName, $Task->getName());
                            } catch (\Throwable $th) {
                                // echo json_encode($th);
                            }
                        }
                        break;
                    case 'taskColumnUpdate':
                        // check if the column & task belong to the team
                        if($columnId && $taskId && $Team->checkTask($taskId) && $Team->checkColumn($columnId))
                        {
                            try {
                                $Task->setFk_column($columnId);
                                $Task->update();
                                LogHistory::create($idUser, 'move', 'task to column', $Task->getName(), 'column id : '.$columnId);
                            } catch (\Throwable $th) {
                                // echo json_encode($th);
                            }
                        }
                        break;
                    case 'upTask':
                        if($columnId && $taskId && $Team->checkTask($taskId) && $Team->checkColumn($columnId))
                        {
                            try {
                                $Task->switchRank($taskId, $columnId, 'up'); 
                                LogHistory::create($idUser, 'up task', 'task', $Task->getName());
                            } catch (\Throwable $th) {
                                // echo json_encode($th);
                            }
                        }
                        break;
                    case 'downTask':
                        if($columnId && $taskId && $Team->checkTask($taskId) && $Team->checkColumn($columnId))
                        {
                            try {
                                $Task->switchRank($taskId, $columnId, 'down');
                                LogHistory::create($idUser, 'down task', 'task', $Task->getName());
                            } catch (\Throwable $th) {
                                // echo json_encode($th);
                            }
                        }
                        break;
                    case 'leftColumn':
                        if($teamId && $columnId && $Team->checkColumn($columnId))
                        {
                            try {
                                $MapColumn->switchRank($columnId, $teamId, 'left');
                                LogHistory::create($idUser, 'move to left', 'column', $MapColumn->getName());
                            } catch (\Throwable $th) {
                                // echo json_encode($th);
                            }
                        }
                        break;
                    case 'rightColumn':
                        if($teamId && $columnId && $Team->checkColumn($columnId))
                        {
                            try {
                                $MapColumn->switchRank($columnId, $teamId, 'right');
                                LogHistory::create($idUser, 'move to right', 'column', $MapColumn->getName());
                            } catch (\Throwable $th) {
                                // echo json_encode($th);
                            }
                        }
                        break;
                    case 'updateColumn':
                        if($columnId && $columnName && $Team->checkColumn($columnId))
                        {
                            try {                 
                                $oldName = $MapColumn->getName();
                                $MapColumn->setName($columnName);
                                $MapColumn->update();
                                LogHistory::create($idUser, 'update', 'column', $oldName,$MapColumn->getName());
                            } catch (\Throwable $th) {
                                // echo json_encode($th);
                            }
                        }
                        break;
                    case 'deleteTaskNote':
                        if($commentId && $Team->checkTaskComment($commentId))
                        {
                            try {
                                $TaskComment->delete();
                                LogHistory::create($idUser, 'delete', 'task comment', $TaskComment->getNote());
                            } catch (\Throwable $th) {
                                // echo json_encode($th);
                            }
                        }
                        break;
                    case 'deleteColumn':
                        if($columnId && $Team->checkColumn($columnId))
                        {
                            try {
                                $MapColumn->delete();
                                LogHistory::create($idUser, 'delete', 'column', $MapColumn->getName());
                            } catch (\Throwable $th) {
                                // echo json_encode($th);
                            }
                        }
                        break;
                    case 'deleteTask':
                        if($taskId && $Team->checkTask($taskId))
                        {
                            try {
                                $Task->delete();
                                LogHistory::create($idUser, 'delete', 'task', $Task->getName());
                            } catch (\Throwable $th) {
                                // echo json_encode($th);
                            }
                        }
                        break;
                    case 'archiveTask':
                        if($taskId && $Team->checkTask($taskId))
                        {
                            try {
                                $Task->setActive(0);
                                $Task->update();
                                LogHistory::create($idUser, 'archive', 'task', $Task->getName());
                            } catch (\Throwable $th) {
                                // echo json_encode($th);
                            }
                        }
                        break;
                    case 'getLastColumnId':
                        try {
                            echo json_encode($MapColumn->fetch_last_insert_id());
                        } catch (\Throwable $th) {
                            // echo json_encode($th);
                        }
                        break;
                    case 'getLastTaskId':
                        try {
                            echo json_encode($Task->fetch_last_insert_id());
                        } catch (\Throwable $th) {
                            // echo json_encode($th);
                        }
                        break;
                    case 'getTaskComments':
                        if($taskId && $Team->checkTask($taskId)) 
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
                                // echo json_encode($th);
                            }
                        }
                        break;
                    case 'getTaskMembers':
                        if($taskId && $Team->checkTask($taskId))
                        {
                            try {                    
                                // get users related to this task
                                $Task = $Task->object_to_array($Task);
                                echo json_encode($Task);
                            } catch (\Throwable $th) {
                                // echo json_encode($th);
                            }
                        }
                        break;
                }
            }
        }
    }
    else
    {
        header("location:".ROOT_URL."index.php");
    }
} else {
    header("location:".ROOT_URL."index.php");
} 

?>