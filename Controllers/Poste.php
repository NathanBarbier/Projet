<?php
//import all models
require_once "../traitements/header.php";

$envoi = $_POST["envoi"] ?? false;
$nomPoste = $_POST["nomPoste"] ?? false;
$idRole = $_POST["idRole"] ?? false;

$idPoste = $_GET["idPoste"] ?? false;

$rights = $_SESSION["habilitation"] ?? false;
$idOrganisation = $_SESSION["idOrganisation"] ?? false;

$Poste = $idPoste ? new Poste($idPoste) : new Poste();

if($action == "updatePoste")
{
    if($rights == "admin")
    {
        if($envoi && $idPoste)
        {
            try
            {
                $Poste->updateName($nomPoste, $idPoste);
            } 
            catch (exception $e)
            {
                header("location:".VIEWS_PATH."admin/gererEntreprise.php?error=fatalerror");
            }
            header("location:".VIEWS_PATH."admin/gererEntreprise.php?success=modifierPoste");
        }
        else
        {
            header("location:".VIEWS_PATH."admin/gererEntreprise.php");
        }
    }
    else 
    {
        header("location:".ROOT_PATH."index.php");
    }
}

if($action == "createPoste")
{    
    if($rights == "admin")
    {
        if($envoi)
        {
            try
            {
                $Poste->create($nomPoste, $idOrganisation, $idRole);
            } 
            catch (exception $e) 
            {
                header("location:".VIEWS_PATH."admin/gererEntreprise.php?error=fatalerror");
            }
            header("location:".VIEWS_PATH."admin/gererEntreprise.php?success=ajouterPoste");
    
        } 
        else 
        {
            header("location:".VIEWS_PATH."admin/gererEntreprise.php");
        }
    } 
    else 
    {
        header("location:".ROOT_PATH."index.php");
    }
}

if($action == "deletePoste")
{
    if($rights == "admin")
    {
        if($idPoste)
        {
            try
            {
                $Poste->delete();
            } 
            catch (exception $e) 
            {
                header("location:".VIEWS_PATH."admin/gererEntreprise.php?error=fatalerror");
            }
            header("location:".VIEWS_PATH."admin/gererEntreprise.php?success=supprimerPoste");
        } 
        else 
        {
            header("location:".VIEWS_PATH."admin/gererEntreprise.php");
        }
    } 
    else 
    {
        header("location:".ROOT_PATH."index.php");
    }
}




?>