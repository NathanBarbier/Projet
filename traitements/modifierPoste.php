<?php
require_once "header.php";

if($_SESSION["habilitation"] == "admin")
{
    if(!empty($_POST["envoi"]) && $_POST["envoi"] = 1 && !empty($_GET["id"]))
    {
        extract($_POST);
        try
        {
            modifierPoste($modifierPoste, $_GET["id"]);
            header("location:../admin/gererEntreprise.php?success=modifierPoste");
        } catch (exception $e) {
            header("location:../admin/gererEntreprise.php?error=fatalerror");
        }
    } else {
        header("location:../admin/gererEntreprise.php");
    }
} else {
    header("location:../index.php");
}