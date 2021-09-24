<?php
//import all models
require_once "../../traitements/header.php";

$action = GETPOST('action');
$idUser = GETPOST('idUser');

$envoi = GETPOST('envoi');

$firstname = GETPOST('firstname');
$lastname = GETPOST('lastname');
$email = GETPOST('email');
$idPoste = GETPOST('idPosition');
$idEquipe = GETPOST('idEquipe');
$birth = GETPOST('birth');

$oldmdp = GETPOST('oldmdp');
$newmdp = GETPOST('newmdp');
$newmdp2 = GETPOST('newmdp2');

$rights = $_SESSION["rights"] ?? false;
$idOrganization = $_SESSION["idOrganization"] ?? false;

$User = new User($idUser);
$Position = new Position();
$Team = new Team();

$members = $User->fetchAll($idOrganization);
$positions = $Position->fetchAll($idOrganization);
$teams = $Team->fetchAll($idOrganization);

$errors = array();
$success = false;

// $data = new stdClass;

$tpl = "listeMembres.php";


if($rights === "admin")
{
    if($action == "updateFirstname")
    {
        if($idUser && $firstname)
        {
            if($envoi)
            {
                $userFirstname = $User->getFirstname();
            
                if($firstname != $userFirstname)
                {
                    try
                    {
                        $status = $User->updateFirstname($firstname, $idUser);
                    } 
                    catch (exception $e)
                    {
                        $errors[] = "Le prénom n'a pas pu être modifié.";
                    }

                    if($status)
                    {
                        $success = "Le prénom a bien été modifié.";
                    }
                    else
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
                $userLastname = $User->getLastname();
            
                if($lastname != $userLastname)
                {
                    try
                    {
                        $User->updateLastname($lastname, $idUser);
                    } 
                    catch (exception $e)
                    {
                        $errors[] = "La modification de nom n'a pas pu aboutir.";
                    }
                    $success = "Le nom a bien été modifié.";
                } 
                else 
                {
                    $errors[] = "Le nom n'a pas été changé.";
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
    
    if($action == "updatePosition")
    {
        if($idUser && $idPoste)
        {
            $status = $User->updatePosition($idPoste, $idUser);

            if($status)
            {
                $success = "La modification de poste a bien été prise en compte.";
            }
            else
            {
                $errors[] = "La modification de poste n'a pas pu aboutir.";
            }
        } 
        else
        {
            header("location:".ROOT_PATH."index.php");
        }
    }

    if($action == "deleteUser")
    {
        if($idUser)
        {
            $status = $User->delete($idUser);

            if($status)
            {
                $success = "La modification de poste a bien été prise en compte.";
            }
            else
            {
                $errors[] = "La modification de poste n'a pas pu aboutir.";
            }
        } 
        else
        {
            header("location:".ROOT_PATH."index.php");
        }
    }


    // Rafraichir les datas si modif bdd
    if($success)
    {
        $members = $User->fetchAll($idOrganization);
        $positions = $Position->fetchAll($idOrganization);
        $teams = $Team->fetchAll($idOrganization);
    }

    require_once VIEWS_PATH."admin/".$tpl;

}
else
{
    header("location:".ROOT_URL."index.php");
}

