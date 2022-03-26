<?php 
// only allow access to ajax request
if( isset( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && ( $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' ) )
{
    if($rights == 'admin' && $idUser > 0 && $idOrganization > 0)
    {
        $action = htmlentities(GETPOST('action'));
        $offset = intval(htmlentities(GETPOST('offset')));

        switch($action)
        {
            case 'loadmore':
                if($offset)
                {
                    try {
                        $users = $Organization->fetchNextUsers($offset);
                        if(is_array($users) && count($users) > 0)
                        {
                            // return new users
                            echo json_encode($users);
                        }
                    } catch (\Throwable $th) {
                        // echo json_encode($th);
                        echo json_encode(false);
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