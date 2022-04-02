<?php 
// import all models
require_once "../../services/header.php";

// only allow access to ajax request
if( isset( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && ( $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' ) )
{
    $rights = $_SESSION["rights"] ?? false;
    $idOrganization = $_SESSION["idOrganization"] ?? null;
    $idUser = $_SESSION['idUser'] ?? null;

    if($rights == 'admin' && $idUser > 0 && $idOrganization > 0)
    {
        $action = htmlentities(GETPOST('action'));
        $offset = intval(htmlentities(GETPOST('offset')));
        $query  = strval(GETPOST('query'));

        $ProjectRepository = new ProjectRepository();

        switch($action)
        {
            case 'loadmore':
                if($offset)
                {
                    try {
                        $projects = $ProjectRepository->fetchNextProjects($idOrganization, $offset);
                        
                        if(is_array($projects) && count($projects) > 0)
                        {
                            // return new users
                            echo json_encode($projects);
                        }
                        else
                        {
                            // there are no more users
                            echo json_encode(false);
                        }
                    } catch (\Throwable $th) {
                        // echo json_encode($th);
                        echo json_encode(false);
                    }
                }
                break;
            case 'search':
                if($query)
                {
                    try {
                        $ProjectRepository = new ProjectRepository();

                        // sql search with pattern
                        $Projects = $ProjectRepository->search($idOrganization, $query);

                        echo json_encode($Projects);
                    } catch (\Throwable $th) {
                        // echo json_encode($th);
                        echo json_encode(false);
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