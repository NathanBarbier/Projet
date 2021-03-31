<?php
require_once "header.php";

if($_SESSION["habilitation"] == "admin")
{
    if(!empty($_POST["envoi"]) && $_POST["envoi"] = 1)
    {
        extract($_POST);
        try
        {
            ajouterPoste($ajoutPoste, $_SESSION["idOrganisation"]);
            header("location:../admin/gererEntreprise.php?success=ajouterPoste");
        } catch (exception $e) {
            header("location:../admin/gererEntreprise.php?error=fatalerror");
        }

    } else {
        header("location:../admin/gererEntreprise.php");
    }
} else {
    header("location:../index.php");
}