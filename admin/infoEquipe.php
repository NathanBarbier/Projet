<?php
require_once "entete.php";
?>
<div class="col-10">

    <div class="container mt-3">
    <?php

    $equipe = recupererEquipe($_SESSION["idOrganisation"], $_GET["idEquipe"]);
    $nbMembresEquipe = recupererNombreMembreEquipe($_SESSION["idOrganisation"], $_GET["idEquipe"]);
    $membresEquipe = recupUtilisateursEquipe($_GET["idEquipe"]);
    $chefEquipe = recupChefEquipe($_GET["idEquipe"]);
    print_r($equipe);
    echo "<br>";
    print_r($nbMembresEquipe);
    echo "<br>";
    print_r($membresEquipe);
    echo "<br>";
    print_r($chefEquipe);
    ?>
    <h1 class="titreInfoEquipe">Fiche d'information équipe</h1>

    <h2><?=$equipe["nomEquipe"];?></h2>  
        <h4 style="margin-left: 8vh">Chef d'équipe : <?= !empty($equipe["chefEquipe"]) ? $chefEquipe["nom"]. " ". $chefEquipe["prenom"] : "non attribué" ;?></h4>
    </div>

