<?php
require_once "layouts/entete.php";
// require_once CONTROLLERS_PATH."Equipe.php";
?>
<div class="col-10">

    <div class="container mt-3">
    <?php


    // print_r($equipe);
    // echo "<br>";
    // print_r($nbMembresEquipe);
    // echo "<br>";
    // print_r($membresEquipe);
    // echo "<br>";
    // print_r($chefEquipe);
    ?>
    <h1 class="titreInfoEquipe">Fiche d'information équipe</h1>

    <h2><?=$equipe["nomEquipe"];?></h2>  
        <h4 style="margin-left: 8vh">Chef d'équipe : <?= !empty($equipe["chefEquipe"]) ? $chefEquipe["nom"]. " ". $chefEquipe["prenom"] : "non attribué" ;?></h4>
    </div>

