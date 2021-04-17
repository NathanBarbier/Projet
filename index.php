<?php
require_once "traitements/header.php";
// session_start();
// // Page de redirection
// // Vérification autorisation accès à la page
// // Si pas connecté
// if(!isset($_SESSION["email"]) || empty($_SESSION["email"]))
// {
//     session_destroy();
//     header("location:pages/connexion.php");
// }

// // Vérification admin
// if(!empty($_SESSION["habilitation"]) && $_SESSION["habilitation"] === "admin"){
//     header("location:admin/index.php");
// }


// // Vérification utilisateur
// if($_SESSION["habilitation"] === "user"){
//     header("location:membres/index.php");
// }

$Organisation = new Organisation(4);
// echo "<pre>";
// print_r($Organisation->getEquipesOrg());
// echo "</pre>";
echo $Organisation->getMinMaxIdEquipe()["maxIdE"];
// echo "<pre>";
// print_r($Organisation->getMinMaxIdEquipe());
// echo "</pre>";
// echo $Organisation->getMinMaxIdEquipe()["minIdE"];
// echo "<br>";
// echo $Organisation->getMinMaxIdEquipe()["maxIdE"];
// echo "<br>";

// echo $Organisation->getEquipesOrg()[3]->getIdEquipe();
// echo "<pre>";
// print_r($Organisation->getInfosUsers());
// print_r($Organisation->getInfosUsers()[1]["idUtilisateur"][0]);
// print_r($Organisation->getUsersOrg());
// echo $Organisation->getIdByNomPrenom("BARBIER", "Nathan");
// echo "</pre>";
