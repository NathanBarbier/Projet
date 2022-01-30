<?php 
// import all models
require_once "../../services/header.php";

$rights = $_SESSION["rights"] ?? false;
$idOrganization = $_SESSION["idOrganization"] ?? null;

if($rights == 'admin')
{
    $action = GETPOST('action');
    $teamId = GETPOST('teamId');

    switch($action)
    {
        case 'getTeamActive':
            if($teamId)
            {
                try {
                    $Team = new Team($teamId);
                    $teamActive = $Team->isActive();
                    echo json_encode($teamActive);
                } catch (\Throwable $th) {
                    echo json_encode($th);
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