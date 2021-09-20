<?php require_once "layouts/entete.php";

$data = json_decode(GETPOST('data'));

// var_dump($data);

?>
<div class="col-10" style="height: 100%;">

    <div class="row w-100" style="height: 100%;">

        <div class="col-8 mt-3" style="height:85%">
            <!-- PROFIL UPDATABLE PROPERTIES -->
            <div class="row profile-section mx-3" style="height: 50%">
                <h1 class="text-center">Projets</h1>
            </div>
            
            <!-- USER PROJECTS OR OTHER THING -->
            <div class="row profile-section mt-3 mx-3" style="height: 50%">
        
            </div>
    
        </div> 

        <!-- USER POSTE / EQUIPE / ROLE -->
        <div class="col-4 profile-section mt-3" style="height:87%">
            <div class="text-center" style="width:150px; height:150px;margin-left:auto; margin-right:auto">
                <img class="mt-5" src="<?= IMG_URL ?>user.png" alt="" width="100px" style="border: 2px black solid;">
            </div>

            <div class="d-flex justify-content-center" style="height: 50%;">



                <div class="mt-5 text-center w-75">
                <!-- <div class="mt-3 w-75"> -->
                    <?php 
                    // $data->avatar 
                    ?>
                <!-- </div> -->
                    <div class="mt-3 pt-2 sticker text-center row">
                        <b><?= $data->lastname ?></b>
                    </div>
                    <button class="w-50 mt-3 pt-2 btn btn-outline-secondary text-center">modifier</button>
                    <div class="mt-4 pt-2 sticker text-center row">
                        <b><?= $data->firstname ?></b>
                    </div>
                    <button class="w-50 mt-3 pt-2 btn btn-outline-secondary text-center">modifier</button>
                    <div class="mt-4 pt-2 sticker text-center row">
                        <b><?= $data->email ?></b>
                    </div>
                    <button class="w-50 mt-3 pt-2 btn btn-outline-secondary text-center">modifier</button>
                </div>



            </div>
        </div>

    </div>


<?php
require_once "layouts/pied.php" ?>