<?php 
require_once "../../services/header.php";
require_once "layouts/entete.php";
?>
<div class="col-10">

    <div class="row mt-4">
        <div class="col pt-4" style="height:35vh">
            <a href="<?= CONTROLLERS_URL ?>admin/collaborateurs.php" class="aVignette">
                <div id="vignette0" class="bg-info mx-auto rounded vignette">
                    <div class="row">
                        <div class="col-4">
                            <div class="vignette-img-container">
                                <img src="<?= IMG_URL ?>user.png" class="img-fluid">
                            </div>
                        </div>
                        <div class="col-8">
                            <h4 class="mt-5 text-start">collaborateurs</h4>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col pt-4" style="height:35vh">
            <a href="<?= CONTROLLERS_URL ?>admin/organisation.php" class="aVignette">
                <div id="vignette1" class="bg-info mx-auto rounded vignette">
                    <div class="row">
                        <div class="col-4">
                            <div class="mt-3 mx-auto text-center">
                                <i class="bi bi-building"></i>
                            </div>
                        </div>
                        <div class="col-8">
                            <h4 class="mt-5 text-start">Organisation</h4>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>
    <div class="row">
        <div class="col pt-4" style="height:35vh">
            <a href="<?= CONTROLLERS_URL ?>admin/projets.php" class="aVignette">
                <div id="vignette2" class="bg-info mx-auto rounded vignette">
                    <div class="row">
                        <div class="col-4">
                            <div class="vignette-img-container">
                                <img src="<?= IMG_URL ?>folder.png" class="img-fluid">
                            </div>
                        </div>
                        <div class="col-8">
                            <h4 class="mt-5 text-start">Projets</h4>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col pt-4" style="height:35vh">
            <div id="vignette3" class="bg-info mx-auto rounded vignette3">
            
            </div>
        </div>
    </div>
<?php
require_once "layouts/pied.php"; ?>




<?php
require_once "layouts/pied.php" 
?>