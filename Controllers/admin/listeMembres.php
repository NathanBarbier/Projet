<?php
//import all models
require_once "../traitements/header.php";

$action = $_GET["action"] ?? $_POST["action"] ?? false;
$idUser = $_GET["idUser"] ?? false;

$envoi = $_POST["envoi"] ?? false;

$firstname = $_POST["prenom"] ?? false;
$lastname = $_POST["nom"] ?? false;
$email = $_POST["email"] ?? false;
$idPoste = $_POST["idPoste"] ?? false;
$idEquipe = $_POST["idEquipe"] ?? false;
$birth = $_POST["birth"] ?? false;

$oldmdp = $_POST["oldmdp"] ?? false;
$newmdp = $_POST["newmdp"] ?? false;
$newmdp2 = $_POST["newmdp2"] ?? false;

$rights = $_SESSION["habilitation"] ?? false;
$idOrganisation = $_SESSION["idOrganisation"] ?? false;

$User = new User($idUser);
$Poste = new Poste();
$Equipe = new Equipe();

$membres = $User->fetchAll($idOrganisation);
$postes = $Poste->fetchAll($idOrganisation);
$equipes = $Equipe->fetchAll($idOrganisation);

$erreurs = array();
$succes = true;

$tpl = "listeMembres.php";

if($rights ===  "admin")
{
    if($action == "updateFirstname")
    {
        if($idUser && $firstname && $rights == "admin")
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
                header("location:".ROOT_PATH."/index.php");
            }
        } 
        else 
        {
            header("location:".ROOT_PATH."/index.php");
        }
    }
    
    if($action == "updateLastname")
    {
        if($idUser && $lastname && $rights == "admin")
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
                    $success[] = "Le nom a bien été modifié.";
                } 
                else 
                {
                    $erreurs[] = "Le nom n'a pas été changé.";
                }
            } 
            else
            {
                header("location:".ROOT_PATH."index.php");
            }
        }
        else
        {
            header("location:".ROOT_PATH."index.php");
        }
    }
    
    if($action == "updateEquipe")
    {
        if($idUser && $idEquipe && $rights == "admin")
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
        if($idUser && $idPoste && $rights == "admin")
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

    );

    $data = json_encode($data);

    header("location:".VIEWS_URL."admin/".$tpl."?data=$data");

}
else
{
    header("location:".ROOT_URL."index.php");
}

