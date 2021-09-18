<?php
//import all models
require_once "../../traitements/header.php";

$action = GETPOST('action');
$idUser = GETPOST('idUser');

$envoi = GETPOST('envoi');

$firstname = GETPOST('firstname');
$lastname = GETPOST('lastname');
$email = GETPOST('email');
$idPoste = GETPOST('idPoste');
$idEquipe = GETPOST('idEquipe');
$birth = GETPOST('birth');

$oldmdp = GETPOST('oldmdp');
$newmdp = GETPOST('newmdp');
$newmdp2 = GETPOST('newmdp2');

$rights = $_SESSION["habilitation"] ?? false;
$idOrganisation = $_SESSION["idOrganisation"] ?? false;

$User = new User($idUser);
$Poste = new Poste();
$Equipe = new Equipe();

$membres = $User->fetchAll($idOrganisation);
$postes = $Poste->fetchAll($idOrganisation);
$equipes = $Equipe->fetchAll($idOrganisation);

$erreurs = array();
$success = false;

$data = new stdClass;

$tpl = "listeMembres.php";

if($rights ===  "admin")
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
                        $User->updateFirstname($firstname);
                    } 
                    catch (exception $e)
                    {
                        $erreurs[] = "Le prénom n'a pas pu être modifié.";
                    }
                    $success = "Le prénom a bien été modifié.";
                } 
                else 
                {
                    $erreurs[] = "Le nom est le même qu'avant.";
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
                        $User->updateLastname($lastname);
                    } 
                    catch (exception $e)
                    {
                        $erreurs[] = "La modification de nom n'a pas pu aboutir.";
                    }
                    $success = "Le nom a bien été modifié.";
                } 
                else 
                {
                    $erreurs[] = "Le nom n'a pas été changé.";
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
    
    if($action == "updateEquipe")
    {
        if($idUser && $idEquipe)
        {
            try
            {
                $User->updateEquipe($idEquipe);
            } 
            catch (exception $e)
            {
                $erreurs[] = "La modification d'équipe n'a pas pu aboutir.";
            }
            $success = "Le modification d'équipe a bien été prise en compte.";
        } 
        else
        {
            header("location:".ROOT_PATH."index.php");
        }
    }
    
    if($action == "updatePoste")
    {
        if($idUser && $idPoste)
        {
            try 
            {
                $User->updatePoste($idPoste);
            }
            catch (exception $e)
            {
                $erreurs[] = "La modification de poste n'a pas pu aboutir.";
            }
            $success = "La modification de poste a bien été prise en compte.";
        } 
        else
        {
            header("location:".ROOT_PATH."index.php");
        }
    }

    $data = array(
        'erreurs' => $erreurs,
        'success' => $success,
        'idOrganisation' => $idOrganisation,
        'User' => $User,
        'membres' => $membres,
        'postes' => $postes,
        'equipes' => $equipes
    );

    $data = json_encode($data);

    header("location:".VIEWS_URL."admin/".$tpl."?data=$data");

}
else
{
    header("location:".ROOT_URL."index.php");
}

