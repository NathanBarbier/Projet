<?php 
// import all models
require_once "../../services/header.php";

$rights = $_SESSION["rights"] ?? false;
$idOrganization = $_SESSION["idOrganization"] ?? null;
$idUser = $_SESSION["idUser"] ?? null;

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
                }
                break;
            }
    }
}
else
{
    header("location:".ROOT_URL."index.php");
}
?>