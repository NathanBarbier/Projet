<?php 
// import all models
require_once "../../services/header.php";
// only allow access to ajax request
if( isset( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && ( $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' ) )
{
    $rights         = $_SESSION["rights"] ?? false;
    $idOrganization = $_SESSION["idOrganization"] ?? null;
    $idUser         = $_SESSION['idUser'] ?? null;
    
    // get the user ip adress
    $ip = $_SERVER['REMOTE_ADDR'];
    $page = "ajax/member/map.php";

    if($rights == 'user' && $idOrganization > 0 && $idUser > 0)
    {
        // security checks
        $projectId  = intval(htmlentities(GETPOST('projectId')));
        $teamId     = intval(htmlentities(GETPOST('teamId')));

        if($projectId > 0 && $teamId > 0)
        {
            $Organization = new Organization();
            $Organization->setRowid($idOrganization);
            $Organization->fetchName();
    
            $CurrentUser = new User($idUser);
    
            $ProjectRepository   = new ProjectRepository();
            $TeamRepository      = new TeamRepository();
            $BelongsToRepository = new BelongsToRepository(); 

            // Security checks
            if($ProjectRepository->checkIfProjectBelongsToOrganization($projectId, $idOrganization))
            {
                if($TeamRepository->checkIfTeamBelongsToProject($projectId, $teamId))
                {
                    if($BelongsToRepository->checkIfUserBelongsToTeam($idUser, $teamId))
                    {
                        $action     = htmlentities(GETPOST('action'));
                        $columnName = htmlentities(GETPOST('columnName'));
                        $columnId   = intval(htmlentities(GETPOST('columnId')));
                        $taskName   = htmlentities(GETPOST('taskName'));
                        $taskId     = intval(htmlentities(GETPOST('taskId')));
                        $taskNote   = htmlentities(GETPOST('taskNote'));
                        $commentId  = intval(htmlentities(GETPOST('commentId')));
                        $memberId   = intval(htmlentities(GETPOST('memberId')));
                    
                        $MapColumn   = new MapColumn($columnId);
                        $Task        = new Task($taskId);
                        $TaskComment = new TaskComment($commentId);
                        $TaskMember  = new TaskMember($memberId, $taskId);

                        // Entirely load the team
                        $Team = new Team($teamId);

                        $Project = new Project();
                        $Project->fetch($projectId, 0);
        
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
                                            
                                            LogHistory::create($idOrganization, $idUser, "INFO", 'update', 'task comment', $TaskComment->getNote(), '', 'comment id : '.$TaskComment->getRowid(), null, $ip);
                                        } catch (\Throwable $th) {
                                            LogHistory::create($idOrganization, $idUser, "ERROR", 'update', 'task comment', $TaskComment->getNote(), '', 'comment id : '.$TaskComment->getRowid(), $th->getMessage(), $ip);
                                        }
                                    }
                                }               
                                break;
                            case 'addTaskComment':
                                // check if the task belong to the team
                                if($taskId && $Team->checkTask($taskId))
                                {
                                    try {
                                        // prepare comment
                                        $TaskComment->setFk_task($taskId);
                                        $TaskComment->setFk_user($idUser);
                                        $TaskComment->setNote('');
                        
                                        // insert comment
                                        $commentId = $TaskComment->create();
        
                                        // update object
                                        $TaskComment->fetch($commentId);
                                        
                                        // convert for JS
                                        $TaskComment = $TaskComment->object_to_array($TaskComment);
        
                                        echo json_encode($TaskComment);
        
                                	    LogHistory::create($idUser, 'create', 'task_comment', $commentId, "task", $TaskComment['rowid'], $idOrganization, "INFO", null, $ip, $page);
        
                                    } catch (\Throwable $th) {
                                        echo json_encode(false);

                                	    LogHistory::create($idUser, 'create', 'task_comment', null, "task", $TaskComment->getFk_task(), $idOrganization, "ERROR", $th->getMessage(), $ip, $page);
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

                                        LogHistory::create($idUser, 'assign', 'user', $memberId, "task", $taskId, $idOrganization, "INFO", null, $ip, $page);
                                    } catch (\Throwable $th) {
                                        LogHistory::create($idUser, 'assign', 'user', $memberId, "task", $taskId, $idOrganization, "ERROR", $th->getMessage(), $ip, $page);
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

                                        LogHistory::create($idUser, 'unassign', 'user', $memberId, "task", $taskId, $idOrganization, "INFO", null, $ip, $page);
                                    } catch (\Throwable $th) {
                                        LogHistory::create($idUser, 'unassign', 'user', $memberId, "task", $taskId, $idOrganization, "ERROR", $th->getMessage(), $ip, $page);
                                    }
                                }
                                break;
                            case 'addColumn':
                                if($columnName && isset($columnName) && !empty($columnName) && $columnName != " " && $columnName != "Open" && $columnName != "Closed")
                                {
                                    try {
                                        $MapColumn->setFk_team($teamId);
                                        $MapColumn->setName($columnName);
                                        $MapColumn->create();
        
                                        // on échange les ranks des deux colonnes 
                                        $ClosedColumn   = new MapColumn($MapColumn->fetchFinishedColumn($teamId)['rowid']);
                                        $CurrentColumn  = new MapColumn($MapColumn->fetch_last_insert_id($teamId));
        
                                        $rank = $ClosedColumn->getRank();
        
                                        $ClosedColumn->setRank($CurrentColumn->getRank());
                                        $CurrentColumn->setRank($rank);
                                        $ClosedColumn->update();
                                        $CurrentColumn->update();
        
                                        $response = array(
                                            'success' => true,
                                        );
        
                                        LogHistory::create($idUser, 'create', 'column', $CurrentColumn->getRowid(), "team", $teamId, $idOrganization, "INFO", null, $ip, $page);
                                    } catch (\Throwable $th) {
                                        $response = array(
                                            'success' => false,
                                            'message' => 'Le nom de colonne est incorrect.',
                                        );
        
                                        LogHistory::create($idUser, 'create', 'column', null, "team", $teamId, $idOrganization, "ERROR", $th->getMessage(), $ip, $page);
                                    }
                                } 
                                else 
                                {
                                    $response = array(
                                        'success' => false,
                                        'message' => 'Le nom de colonne est incorrect.',
                                    );
                                }
                                echo json_encode($response);
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
                                        $taskId = $Task->fetch_last_insert_id();
        
                                        echo json_encode($Task);
                                        
                                        LogHistory::create($idUser, 'create', 'task', $taskId, "team", $teamId, $idOrganization, "INFO", null, $ip, $page);
                                    } catch (\Throwable $th) {
                                        // echo json_encode($th);
                                        echo json_encode(false);
        
                                        LogHistory::create($idUser, 'create', 'task', null, "team", $teamId, $idOrganization, "ERROR", $th->getMessage(), $ip, $page);
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

                                        LogHistory::create($idUser, 'update', 'task', $taskId, "team", $teamId, $idOrganization, "INFO", null, $ip, $page);
                                    } catch (\Throwable $th) {
                                        LogHistory::create($idUser, 'update', 'task', $taskId, "team", $teamId, $idOrganization, "ERROR", $th->getMessage(), $ip, $page);
                                    }
                                }
                                break;
                            case 'taskColumnUpdate':
                                // check if the column & task belong to the team
                                if($columnId && $taskId && $Team->checkTask($taskId) && $Team->checkColumn($columnId))
                                {
                                    try {
                                        $Column = new MapColumn($columnId);
                                    
                                        if($Column->getName() === "Closed")
                                        {
                                            $now = new DateTime();
                                            $Task->setActive(-1);
                                            $Task->setFinished_at($now->format("Y-m-d H:i:s"));
                                        } 
                                        else 
                                        {
                                            $Task->setActive(1);
                                        }

                                        $Task->setFk_column($columnId);
                                        $Task->update();

                                        LogHistory::create($idUser, 'change column', 'task', $taskId, "team", $teamId, $idOrganization, "INFO", null, $ip, $page);
                                    } catch (\Throwable $th) {
                                        LogHistory::create($idUser, 'change column', 'task', $taskId, "team", $teamId, $idOrganization, "ERROR", $th->getMessage(), $ip, $page);
                                    }
                                }
                                break;
                            case 'finishedTask' :
                                if($taskId && $Team->checkTask($taskId) && $oldColumn !== "Closed")
                                {
                                    try {
                                        $Column = new MapColumn();
        
                                        $closedColumn = $Column->fetchFinishedColumn($teamId);
        
                                        $now = new DateTime();
        
                                        $Task->setActive(-1);
                                        $Task->setFinished_at($now->format("Y-m-d H:i:s"));
                                        $Task->setFk_column($closedColumn["rowid"]);
                                        $Task->update();
        
                                        echo json_encode(true);
        
                                        LogHistory::create($idUser, 'finish', 'task', $taskId, "team", $teamId, $idOrganization, "INFO", null, $ip, $page);
                                    } catch (\Throwable $th) {
                                        echo json_encode(false);
        
                                        LogHistory::create($idUser, 'finish', 'task', $taskId, "team", $teamId, $idOrganization, "ERROR", $th->getMessage(), $ip, $page);
                                    }
                                }
                                break;
                            case 'upTask':
                                if($columnId && $taskId && $Team->checkTask($taskId) && $Team->checkColumn($columnId))
                                {
                                    try {
                                        $Task->switchRank($taskId, $columnId, 'up'); 

                                        LogHistory::create($idUser, 'up', 'task', $taskId, "column", $columnId, $idOrganization, "INFO", null, $ip, $page);
                                    } catch (\Throwable $th) {
                                        LogHistory::create($idUser, 'up', 'task', $taskId, "column", $columnId, $idOrganization, "ERROR", $th->getMessage(), $ip, $page);
                                    }
                                }
                                break;
                            case 'downTask':
                                if($columnId && $taskId && $Team->checkTask($taskId) && $Team->checkColumn($columnId))
                                {
                                    try {
                                        $Task->switchRank($taskId, $columnId, 'down');
                                        
                                        LogHistory::create($idUser, 'down', 'task', $taskId, "column", $columnId, $idOrganization, "INFO", null, $ip, $page);
                                    } catch (\Throwable $th) {
                                        LogHistory::create($idUser, 'down', 'task', $taskId, "column", $columnId, $idOrganization, "ERROR", $th->getMessage(), $ip, $page);
                                    }
                                }
                                break;
                            case 'leftColumn':
                                if($teamId && $columnId && $Team->checkColumn($columnId) && $columnName != "Open" && $columnName != "Closed")
                                {
                                    try {
                                        $status = $MapColumn->switchRank($columnId, $teamId, 'left');

                                        LogHistory::create($idUser, 'move to left', 'column', $columnId, "team", $teamId, $idOrganization, "INFO", null, $ip, $page);
                                    } catch (\Throwable $th) {
                                        LogHistory::create($idUser, 'move to left', 'column', $columnId, "team", $teamId, $idOrganization, "ERROR", $th->getMessage(), $ip, $page);
                                    }
                                } 
                                else 
                                {
                                    $status = false;
                                }
                                echo json_encode($status);
                                break;
                            case 'rightColumn':
                                if($teamId && $columnId && $Team->checkColumn($columnId) && $columnName != "Open" && $columnName != "Closed")
                                {
                                    try {
                                        $status = $MapColumn->switchRank($columnId, $teamId, 'right');

                                        LogHistory::create($idUser, 'move to right', 'column', $columnId, "team", $teamId, $idOrganization, "INFO", null, $ip, $page);
                                    } catch (\Throwable $th) {
                                        LogHistory::create($idUser, 'move to right', 'column', $columnId, "team", $teamId, $idOrganization, "ERROR", $th->getMessage(), $ip, $page);
                                    }
                                } 
                                else 
                                {
                                    $status = false;
                                }
                                echo json_encode($status);
                                break;
                            case 'updateColumn':
                                // check if the column belong to the team
                                if($columnId && $columnName && $Team->checkColumn($columnId) && $columnName != " " && $columnName != "Open" && $columnName != "Closed" && $oldName != "Open" && $oldName != "Closed")
                                {
                                    try {
                                        $oldName = $MapColumn->getName();
                                        $MapColumn->setName($columnName);
                                        $MapColumn->update();
        
                                        $response = array(
                                            'success' => true,
                                        );
        
                                        LogHistory::create($idUser, 'update', 'column', $columnId, "team", $teamId, $idOrganization, "INFO", null, $ip, $page);
                                    } catch (\Throwable $th) {
        
                                        $response = array(
                                            'success' => false,
                                            'message' => 'Le nom de colonne est incorrect.',
                                        );
        
                                        LogHistory::create($idUser, 'update', 'column', $columnId, "team", $teamId, $idOrganization, "ERROR", $th->getMessage(), $ip, $page);
                                    }
                                } 
                                else 
                                {
                                    $response = array(
                                        'success' => false,
                                        'message' => 'Le nom de colonne est incorrect.',
                                    );
                                }
                                echo json_encode($response);
                                break;
                            case 'deleteTaskNote':
                                if($commentId && $Team->checkTaskComment($commentId))
                                {
                                    try {
                                        // check if is author
                                        $authorId = $TaskComment->getFk_user();
                                        if($authorId == $idUser)
                                        {
                                            $TaskComment->delete();
                                            
                                            LogHistory::create($idUser, 'delete', 'task_comment', $commentId, "task", $taskId, $idOrganization, "INFO", null, $ip, $page);
                                        }
                                    } catch (\Throwable $th) {
                                        LogHistory::create($idUser, 'delete', 'task_comment', $commentId, "task", $taskId, $idOrganization, "ERROR", $th->getMessage(), $ip, $page);
                                    }
                                }
                                break;
                            case 'deleteColumn':
                                if($columnId && $Team->checkColumn($columnId) && $MapColumn->getName() != "Open" && $MapColumn->getName() != "Closed")
                                {
                                    try {
                                        // before deleting column move all task related to it to the 'open' column
                                        $status = $MapColumn->moveTasksToOpen();
                                        if($status)
                                        {
                                            $MapColumn->delete();
                                            
                                            LogHistory::create($idUser, 'delete', 'column', $columnId, "team", $teamId, $idOrganization, "INFO", null, $ip, $page);
                                        }
                                    } catch (\Throwable $th) {
                                        LogHistory::create($idUser, 'delete', 'column', $columnId, "team", $teamId, $idOrganization, "ERROR", $th->getMessage(), $ip, $page);
                                    }
                                }
                                break;
                            case 'deleteTask':
                                if($taskId && $Team->checkTask($taskId))
                                {
                                    try {
                                        $Task->delete();
                                        
                                        LogHistory::create($idUser, 'delete', 'task', $taskId, "team", $teamId, $idOrganization, "INFO", null, $ip, $page);
                                    } catch (\Throwable $th) {
                                        LogHistory::create($idUser, 'delete', 'task', $taskId, "team", $teamId, $idOrganization, "ERROR", $th->getMessage(), $ip, $page);
                                    }
                                }
                                break;
                            case 'archiveTask':
                                if($taskId && $Team->checkTask($taskId))
                                {
                                    try {
                                        $Task->setActive(0);
                                        $Task->update();

                                        LogHistory::create($idOrganization, $idUser, "INFO", 'archive', 'task', $Task->getName(), null, 'task id : '.$Task->getRowid(), null, $ip);
                                    } catch (\Throwable $th) {
                                        LogHistory::create($idOrganization, $idUser, "ERROR", 'archive', 'task', $Task->getName(), null, 'task id : '.$Task->getRowid(), $th->getMessage(), $ip);
                                    }
                                }
                                break;
                            case 'getLastColumnId':
                                try {
                                    echo json_encode($MapColumn->fetch_last_insert_id($teamId));
                                } catch (\Throwable $th) {
                                    echo json_encode(false);
                                }
                                break;
                            case 'getLastTaskId':
                                try {
                                    echo json_encode($Task->fetch_last_insert_id());
                                } catch (\Throwable $th) {
                                    echo json_encode(false);
                                }
                                break;
                            case 'getTaskComments':
                                if($taskId && $Team->checkTask($taskId)) 
                                {
                                    try {
                                        $Comments = $Task->getComments();
                        
                                        foreach($Comments as $key => $Comment)
                                        {
                                            $User = new User($Comment->getFk_user());
                                            
                                            // set the comment username
                                            $Comments[$key]->author = $User->getLastname() . " " . $User->getFirstname();
        
                                            // for color
                                            $Comments[$key]->admin = $User->isAdmin() ? true : false;
                    
                                            // for authorizations
                                            $Comments[$key]->isAuthor = $Comment->getFk_user() == $idUser ? true : false;
                                        }
                    
                                        // return the task
                                        $Comments = $Task->object_to_array($Task);
                        
                                        echo json_encode($Comments);
                                    } catch (\Throwable $th) {
                                        // echo json_encode($th);
                                        echo json_encode(false);
                                    }
                                }
                                else
                                {
                                    echo json_encode(false);
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
                                        echo json_encode(false);
                                    }
                                }
                                break;
                            case 'getTeamMembers':
                                if($taskId && $Team->checkTask($taskId))
                                {
                                    $taskMembers    = $Task->getMembers();
                                    $teamMembers    = $Team->getUsers();
        
                                    $affectedUsers  = array();
                                    $freeUsers      = array();
        
                                    foreach($teamMembers as $teamMember)
                                    {
                                        $free = true;
                                        foreach($taskMembers as $taskMember)
                                        {
                                            if($teamMember->getRowid() == $taskMember->getRowid())
                                            {
                                                $free = false;
                                                $affectedUsers[] = $Team->object_to_array($teamMember);
                                                break;
                                            }
                                        }
        
                                        if($free == true)
                                        {
                                            $freeUsers[] = $Team->object_to_array($teamMember);
                                        }
                                    }
        
                                    $data = array(
                                        "affectedUsers" => $affectedUsers,
                                        "freeUsers"     => $freeUsers
                                    );
        
                                    echo json_encode($data);
                                }
                                break;
                        }
                    }
                    else
                    {
                        header("location:".ROOT_URL."index.php");
                    }
                }
                else
                {
                    header("location:".ROOT_URL."index.php");
                }
            }
            else
            {
                header("location:".ROOT_URL."index.php");
            }
        }
    }
    else
    {
        header("location:".ROOT_URL."index.php");
    }
}
else
{
    header("location:".ROOT_URL."index.php");
}
?>