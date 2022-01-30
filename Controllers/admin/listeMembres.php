<?php
//import all models
require_once "../../services/header.php";

$rights = $_SESSION["rights"] ?? false;
$idOrganization = $_SESSION["idOrganization"] ?? false;

if($rights === "admin")
{
    $action = GETPOST('action');
    $idUser = intval(GETPOST('idUser'));
    $envoi = GETPOST('envoi');

    $firstname = GETPOST('firstname');
    $lastname = GETPOST('lastname');
    $email = GETPOST('email');
    $idEquipe = intval(GETPOST('idEquipe'));
    $birth = GETPOST('birth');

    $oldmdp = GETPOST('oldmdp');
    $newmdp = GETPOST('newmdp');
    $newmdp2 = GETPOST('newmdp2');

    $Organization = new Organization($idOrganization);

    $errors = array();
    $success = false;

    $tpl = "listeMembres.php";

    if($action == "updateFirstname")
    {
        if($idUser && $firstname)
        {
            if($envoi)
            {
                $User = $Organization->fetchUser($idUser);
                if($firstname != $User->getFirstname())
                {
                    try
                    {
                        $oldFirstname = $User->getFirstname(),
                        $User->setFirstname($firstname);
                        $User->update();
                        LogHistory::create($idUser, 'update firstname', 'user', $User->getLastname().' '.$oldFirstname, $User->getFirstname());
                        $success = "Le prénom a bien été modifié.";
                    } 
                    catch (exception $e)
                    {
                        $errors[] = "Le prénom n'a pas pu être modifié.";
                    }
                } 
                else 
                {
                    $errors[] = "Le nom est le même qu'avant.";
                }
            } 
            else 
            {
                header("location:".ROOT_URL."/index.php");
            }
        } 
        else 
        {
            header("location:".ROOT_URL."/index.php");
        }
    }
    
    if($action == "updateLastname")
    {
        if($idUser && $lastname)
        {
            if($envoi)
            {
                $User = $Organization->fetchUser($idUser);
                if($lastname != $User->getLastname())
                {
                    try
                    {
                        $oldLastname = $User->getLastname();
                        $User->setLastname($lastname);
                        $User->update();
                        LogHistory::create($idUser, 'update lastname', 'user', $oldLastname.' '.$User->getFirstname(), $User->getLastname());
                        $success = "Le nom a bien été modifié.";
                    } 
                    catch (exception $e)
                    {
                        $errors[] = "La modification de nom n'a pas pu aboutir.";
                    }
                } 
                else 
                {
                    $errors[] = "Le nom n'a pas été modifié.";
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

    if($action == "deleteUser")
    {
        if($idUser)
        {
            $User = $Organization->fetchUser($idUser);
            try {
                $Organization->removeUser($idUser);
                $User->delete();
                LogHistory::create($idUser, 'delete', 'user', $User->getLastname().' '.$User->getFirstname());
                $success = "La suppression d'utilisateur a bien été effectuée.";
            } catch (\Throwable $th) {
                //throw $th;
                $errors[] = "La suppression d'utilisateur n'a pas pu aboutir.";
            }
        } 
        else
        {
            header("location:".ROOT_PATH."index.php");
        }
    }

    require_once VIEWS_PATH."admin/".$tpl;

}
else
{
    header("location:".ROOT_URL."index.php");
}

