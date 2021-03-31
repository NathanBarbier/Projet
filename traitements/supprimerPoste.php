<?php
require_once "header.php";

if($_SESSION["habilitation"] == "admin")
{
    if(!empty($_GET["suppr"]))
    {
        extract($_GET);
        try
        {
            supprPoste($suppr, $_SESSION["idOrganisation"]);
            header("location:../admin/gererEntreprise.php?success=supprimerPoste");
        } catch (exception $e) {
            header("location:../admin/gererEntreprise.php?error=fatalerror");
        }

    } else {
        header("location:../admin/gererEntreprise.php");
    }
} else {
    header("location:../index.php");
}