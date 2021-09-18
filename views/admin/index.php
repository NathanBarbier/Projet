<?php 
require_once "layouts/entete.php";
?>
<div class="col-10">
    <!-- <aside class="bd-sidebar">
        <nav class="bd-subnavbar py-2">
            <ul class="list-unstyled mb-0 py-3 pt-md-1">
                <li class="mb-1">
                    <button class="nav-item btn btn-secondary">test</button>
                </li>
                <li class="mb-1">
                    <button class="nav-item btn btn-secondary">test</button>
                </li>
                <li class="mb-1">
                    <button class="nav-item btn btn-secondary">test</button>
                </li>
            </ul>
        </nav>
    </aside> -->

    <a class="btn btn-primary" href="<?= VIEWS_URL ?>admin/infoEquipe.php">Gérer les équipes</a>
    <a class="btn btn-primary" href="<?= VIEWS_URL ?>admin/gestionOrganisation.php">Gérer l'organisation</a>

    <div class="bg-secondary" style="height:50vh; width: 100vh">
    </div>

    <?php
require_once "layouts/pied.php" ?>