<?php
session_start();
// Page de redirection
// Vérification autorisation accès à la page
// Si pas connecté
if(!isset($_SESSION["email"]) || empty($_SESSION["email"]))
{
    session_destroy();
    header("location:pages/connexion.php");
}

// Vérification admin
if(!empty($_SESSION["habilitation"]) && $_SESSION["habilitation"] === "admin"){
    header("location:admin/index.php");
}


// Vérification utilisateur
if($_SESSION["habilitation"] === "user"){
    header("location:membres/index.php");
}
