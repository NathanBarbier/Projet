<?php 
require_once "layouts/entete.php";
?>
<div class="col-10">

    <div class="row mt-4">
        <div class="col pt-4" style="height:35vh">
            <a href="<?= CONTROLLERS_URL ?>admin/inscriptionUtilisateur.php" class="aVignette">
                <div id="vignette0" class="bg-info mx-auto rounded vignette">
                    <div class="row">
                        <div class="col-4">
                            <div style="height: 10vh; width:10vh; margin-top:9vh; margin-left:2vh">
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
        <div class="col pt-4" style="height:35vh">
            <a href="<?= CONTROLLERS_URL ?>admin/listeMembres.php" class="aVignette">
                <div id="vignette1" class="bg-info mx-auto rounded vignette">
                    <div class="row">
                        <div class="col-4">
                            <div style="height: 10vh; width:10vh; margin-top:9vh; margin-left:2vh">
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
    <div class="row">
        <div class="col pt-4" style="height:35vh">
            <a href="<?= CONTROLLERS_URL ?>admin/postesEquipes.php" class="aVignette">
                <div id="vignette2" class="bg-info mx-auto rounded vignette">
                    <div class="row">
                        <div class="col-4">
                            <div style="height: 10vh; width:10vh; margin-top:9vh; margin-left:2vh">
                                <img src="<?= IMG_URL ?>team.png" class="img-fluid">
                            </div>
                        </div>
                        <div class="col-8">
                            <h4 class="mt-5 text-start">Gérer les postes et les équipes</h4>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col pt-4" style="height:35vh">
            <div id="vignette3" class="bg-info mx-auto rounded vignette">
            
            </div>
        </div>
    </div>
<?php
require_once "layouts/pied.php"; ?>