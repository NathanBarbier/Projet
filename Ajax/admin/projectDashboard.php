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

    if($rights == 'admin' && $idUser > 0 && $idOrganization > 0)
    {
        $action = htmlentities(GETPOST('action'));
        $teamId = intval(GETPOST('teamId'));

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
                        LogHistory::create($idOrganization, $idUser, "ERROR", 'get active teams', '', '', null, null, $th->getMessage(), $ip);
                    }
                    break;
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