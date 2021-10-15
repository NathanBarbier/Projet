<?php 
require_once "layouts/entete.php";
?>

<div class="col-10" style="height: 100%;">
    <div class="row w-100" style="height: 100%;">
        <div class="col-8 mt-3 position-relative" style="height:87%;">
            
        <?php if($success) { ?>
            <div class="alert alert-success w-50 text-center position-absolute top-0 start-50 translate-middle-x">
                <?= $success ?>
            </div>
            <?php } else if ($errors) { ?>
            <div class="alert alert-danger w-50 text-center position-absolute top-0 start-50 translate-middle-x">
                <?php foreach($errors as $error) {
                    echo $error . "<br>";
                } ?>
            </div>
        <?php } ?>
            <div class="row mx-3" style="height: 100%;">
                <h3 class="mx-auto text-center border-bottom w-50">Projets Actuels</h3>

                <div style="height: 90%; overflow: auto">
                    <?php 
                    if($userProjects)
                    {
                        foreach($userProjects as $project)
                        {
                        ?>
                        <div class="pb-3 mb-5 border-lg" style="height:max-content;">
                            <div class="row text-center justify-content-around">
                                <div class="col-10 sticker mt-3 pt-3">
                                    <p><b>Nom du projet : </b><?= $project->projectName ?></p>
                                </div>
                            </div>
                            <div class="row text-center justify-content-around">
                                <div class="col-10 sticker mt-4 pt-3">
                                    <p><b>Equipe : </b><?= $project->teamName ?></p>
                                </div>
                            </div>
                            <div class="row mt-4 justify-content-around">
                                <div class="sticker col-4 text-center">
                                    <b>Nb participants</b>
                                    <p class="text-center"><?php echo $project->membersCount ?></p>
                                    <br>
                                </div>
                                
                                <div class="sticker col-4 text-center">
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

            <div class="d-flex justify-content-center mt-4">
                <div class="text-center w-75">
                    <form action="<?= CONTROLLERS_URL ?>membres/tableauDeBord.php?action=userUpdate" method="POST">

                        <input type="text" name="lastname" class="sticker form-control pt-2 text-center" value="<?= $CurrentUser->lastname ?>">
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