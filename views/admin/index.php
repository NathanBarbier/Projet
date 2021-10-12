<?php 
require_once "../../traitements/header.php";
require_once "layouts/entete.php";
?>
<div class="col-10">

    <div class="mt-3">
        <a class="btn btn-primary" href="<?= CONTROLLERS_URL ?>admin/postesEquipes.php">Postes et équipes</a>
        <a class="btn btn-primary" href="<?= CONTROLLERS_URL ?>admin/gestionOrganisation.php">Gérer l'organisation</a>
    </div>

    <div class="bg-secondary mt-2" style="height:50vh; width: 100vh">
    </div>

    <?php
require_once "layouts/pied.php" ?>