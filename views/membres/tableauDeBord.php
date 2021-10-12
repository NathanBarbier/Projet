<?php 
require_once "layouts/entete.php";
?>

<div class="col-10 mt-3" style="height: 100%;">
<?php

if($success)
{ ?>
    <div class="alert alert-success w-50 mx-auto text-center">
        <?= $success ?>
    </div>
    <?php 
}
else if ($erreurs)
{ ?>
    <div class="alert alert-danger w-50 mx-auto text-center">
        <?php 
        foreach($erreurs as $erreur)
        {
            echo $erreur . "<br>";
        }
        ?>
    </div>
<?php
} ?>

    <div class="row w-100" style="height: 100%;">

        <div class="col-8 mt-3" style="height:87%">
            <!-- PROJECT -->
            <div class="row profile-section mx-3" style="height: 100%;">
                <h1 class="text-center">Projets Actuels</h1>

                <div style="height: 90%; overflow: auto">
                    <?php 
                    if($userProjects)
                    {
                        foreach($userProjects as $project)
                        {
                        ?>
                        <div class="sticker pb-3 mb-5" style="height:max-content;">
                            <div class="row text-center justify-content-around">
                                <div class="col-10 sticker-deep mt-3 pt-3">
                                    <p><b>Nom du projet : </b><?= $project->projectName ?></p>
                                </div>
                            </div>
                            <div class="row text-center justify-content-around">
                                <div class="col-10 sticker-deep mt-4 pt-3">
                                    <p><b>Equipe : </b><?= $project->teamName ?></p>
                                </div>
                            </div>
                            <div class="row mt-4 justify-content-around">
                                <div class="sticker-deep col-4 text-center">
                                    <b>Nb participants</b>
                                    <p class="text-center"><?php echo $project->membersCount ?></p>
                                    <br>
                                </div>
                                
                                <div class="sticker-deep col-4 text-center">
                                    <b>Nb tâches</b>
                                    <br>
                                    <p class="text-center"><?php echo $project->tasksCount ?></p>
                                </div>
                            </div>
                            <div class="row">
                                <a href="<?= CONTROLLERS_URL ?>membres/map.php?projectId=<?= $project->rowid ?>" class="btn btn-outline-primary w-25 mt-3 mx-auto">Aller sur la Map</a>
                            </div>
                        </div>
                        <?php 
                        }
                    }
                    else
                    { ?>
                        <div class="sticker-deep mx-auto mt-5 pt-3 text-center" style="width: 70%; height: 30%">
                            <h3>Vous n'avez encore aucun projet en cours.</h3>
                        </div>
                    <?php
                    } ?>

                </div>
            </div>
        </div> 

        <!-- user properties -->
        <div class="col-4 profile-section mt-3" style="height:87%">

            <h3 class="mx-auto mt-3 text-center" style="border-bottom: black solid 1px; border-color: rgb(216, 214, 214); width: 80%">Profil</h3>

            <div class="text-center" style="width:150px; height:150px;margin-left:auto; margin-right:auto">
                <img class="mt-4" src="<?= IMG_URL ?>user.png" alt="" width="100px" style="border: 2px black solid;">
            </div>

            <div class="d-flex justify-content-center" style="height: 50%;">
                <div class="mt-4 text-center w-75">
                    <form action="<?= CONTROLLERS_URL ?>membres/tableauDeBord.php?action=userUpdate" method="POST">

                        <input type="text" name="lastname" class="sticker form-control mt-4 pt-2 text-center" value="<?= $CurrentUser->lastname ?>">
                        <input type="text" name="firstname" class="sticker form-control mt-4 pt-2 text-center" value="<?= $CurrentUser->firstname ?>">
                        <input type="email" name="email" class="sticker form-control mt-4 pt-2 text-center" value="<?= $CurrentUser->email ?>">
    
                        <button type="submit" class="w-50 mt-5 pt-2 btn btn-outline-primary text-center">Update</button>
                    </form>

                    <a class="btn btn-outline-secondary mt-5" href="<?= CONTROLLERS_URL ?>membres/passwordUpdate.php">Modifier mot de passe</a>
                        <div class="sticker-deep text-center pt-2 mt-3">
                            <b>Poste : </b>
                            <?= $CurrentUser->position; ?>
                        </div>
                        <div class="sticker-deep text-center pt-2 mt-3">
                            <b>Role : </b>
                            <?= $CurrentUser->role; ?>
                        </div>
                </div>
            </div>
        </div>
    </div>
<?php
require_once "layouts/pied.php" ?>