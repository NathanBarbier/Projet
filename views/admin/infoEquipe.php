<?php
require_once "layouts/entete.php";
// require_once CONTROLLERS_PATH."Equipe.php";

$data = json_decode(GETPOST('data'));

// var_dump($data);

$Equipe = $data->Equipe ?? '';
$ChefEquipe = $data->ChefEquipe ?? '';

// $Equipe = json_decode($data)
?>
<div class="col-10">

    <div class="container mt-3">
    <?php

    ?>
    <h1 class="titreInfoEquipe">Fiche d'information équipe</h1>

    <h2><?= $Equipe->nom; ?></h2>  
        <h4 style="margin-left: 8vh">Chef d'équipe : <?= !empty($ChefEquipe) ? $ChefEquipe->nom. " ". $ChefEquipe->prenom : "non attribué" ;?></h4>
    </div>

</div>

<?php 
require_once "layouts/pied.php";
?>

