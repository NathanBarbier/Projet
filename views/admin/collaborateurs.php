<?php 
require_once "layouts/entete.php";
?>
    <div class="row mt-4">
        <div class="col pt-4 vignette-container">
            <a href="<?= CONTROLLERS_URL ?>admin/inscriptionUtilisateur.php" class="aVignette">
                <div id="vignette0" class="bg-info mx-auto rounded vignette">
                    <div class="row">
                        <div class="col-4">
                            <div class="vignette-img-container">
                                <img src="<?= IMG_URL ?>user.png" class="img-fluid">
                            </div>
                        </div>
                        <div class="col-8">
                            <h4 class="mt-5 text-start">Gérer inscriptions</h4>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col pt-4 vignette-container">
            <a href="<?= CONTROLLERS_URL ?>admin/listeMembres.php" class="aVignette">
                <div id="vignette1" class="bg-info mx-auto rounded vignette">
                    <div class="row">
                        <div class="col-4">
                            <div class="vignette-img-container">
                                <img src="<?= IMG_URL ?>list.png" width="120%">
                            </div>
                        </div>
                        <div class="col-8">
                            <h4 class="mt-5 text-start">Liste Membres</h4>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>
<?php
require_once "layouts/pied.php"; ?>