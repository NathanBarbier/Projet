<?php
require_once "header.php";

$envoi = $_POST["envoi"] ? $_POST["envoi"] : false;
$nomPoste = $_POST["nomPoste"] ? $_POST["nomPoste"] : false;
$idRole = $_POST["idRole"] ? $_POST["idRole"] : false;

$idPoste = $_GET["idPoste"] ? $_GET["idPoste"] : false;

$rights = $_SESSION["habilitation"] ? $_SESSION["habilitation"] : false;
$idOrganisation = $_SESSION["idOrganisation"] ? $_SESSION["idOrganisation"] : false;

$Poste = $idPoste ? new Poste($idPoste) : new Poste();

if($action == "updatePoste")
{
    if($rights == "admin")
    {
        if($envoi && $idPoste)
        {
            try
            {
                $Poste->update($nomPoste, $_GET["id"]);
            } 
            catch (exception $e)
            {
                header("location:../admin/gererEntreprise.php?error=fatalerror");
            }
            header("location:../admin/gererEntreprise.php?success=modifierPoste");
        }
        else
        {
            header("location:../admin/gererEntreprise.php");
        }
    }
    else 
    {
        header("location:../index.php");
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
                header("location:../admin/gererEntreprise.php?error=fatalerror");
            }
            header("location:../admin/gererEntreprise.php?success=ajouterPoste");
    
        } 
        else 
        {
            header("location:../admin/gererEntreprise.php");
        }
    } 
    else 
    {
        header("location:../index.php");
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
                header("location:../admin/gererEntreprise.php?error=fatalerror");
            }
            header("location:../admin/gererEntreprise.php?success=supprimerPoste");
        } 
        else 
        {
            header("location:../admin/gererEntreprise.php");
        }
    } 
    else 
    {
        header("location:../index.php");
    }
}




?>