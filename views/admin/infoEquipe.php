<?php
require_once "layouts/entete.php";
// require_once CONTROLLERS_PATH."Equipe.php";

// var_dump($data);

$Equipe = $Equipe ?? '';

// $Equipe = json_decode($data)
?>
<div class="col-10">

    <div class="container mt-3">
    <?php

    ?>
    <h1 class="titreInfoEquipe">Fiche d'information équipe</h1>

    <h2><?= $Equipe->nom; ?></h2>  
        
    </div>

</div>

<?php 
require_once "layouts/pied.php";
?>

