<?php 
// import all models
require_once "../../services/header.php";
// only allow access to ajax request
if( isset( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && ( $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' ) )
{
    $rights = $_SESSION["rights"] ?? false;
    $idOrganization = $_SESSION["idOrganization"] ?? null;
    $idUser = $_SESSION["idUser"] ?? null;
    // get the user ip adress
    $ip = $_SERVER['REMOTE_ADDR'];
    $page = "ajax/admin/projectDashboard.php";

    if($rights == 'admin' && $idUser > 0 && $idOrganization > 0)
    {
        $action = htmlentities(GETPOST('action'));
        $teamId = intval(GETPOST('teamId'));
        $offset = intval(htmlentities(GETPOST('offset')));
        $query  = strval(GETPOST('query'));

        $UserRepository = new UserRepository();

        switch($action)
        {
            case 'getTeamActive':
                if($teamId)
                {
                    try {
                        $Team = new Team($teamId);
                        echo json_encode($Team->isActive());
                    } catch (\Throwable $th) {
                        // echo json_encode($th);
                        LogHistory::create($idUser, 'fetch', 'team', $teamId, 'project', $Team->getFk_project(), $idOrganization, "ERROR", $th->getMessage(), $ip, $page);
                    }
                }
                break;
            case 'loadmore':
                if($offset)
                {
                    try {
                        $users = $UserRepository->fetchNextUsers($idOrganization, $offset);
                        
                        if(is_array($users) && count($users) > 0)
                        {
                            // return new users
                            echo json_encode($users);
                        }
                        else
                        {
                            // there are no more users
                            echo json_encode(false);
                        }
                    } catch (\Throwable $th) {
                        // echo json_encode($th);
                        echo json_encode(false);
                        LogHistory::create($idUser, 'loadmore', 'user', null, null, null, $idOrganization, "ERROR", $th->getMessage(), $ip, $page);
                    }
                }
                break;
            case 'search':
                if($query)
                {
                    try {
                        $UserRepository = new UserRepository();

                        // sql search with pattern
                        $Users = $UserRepository->searchNonAdminUsersFromFirstnameAndLastname($idOrganization, $query);

                        echo json_encode($Users);
                    } catch (\Throwable $th) {
                        // echo json_encode($th);
                        echo json_encode(false);
                        LogHistory::create($idUser, 'fetch', 'user', null, null, null, $idOrganization, "ERROR", $th->getMessage(), $ip, $page);
                    }
                }
                break;
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