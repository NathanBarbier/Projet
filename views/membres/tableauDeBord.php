<?php 
require_once "layouts/entete.php";
?>

<div class="col-12" style="height: 100%;">
    <div class="row w-100" style="height: 100%;">
        <div class="offset-1 col-8 mt-3 position-relative" style="height:87%;">
            <?php if($success) { ?>
                <div class="alert alert-success w-50 text-center position-absolute top-0 start-50 translate-middle-x">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    <?= $success ?>
                </div>
                <?php } else if ($errors) { ?>
                <div class="alert alert-danger w-50 text-center position-absolute top-0 start-50 translate-middle-x">
                    <?php foreach($errors as $error) { ?>
                        <i class="bi bi-exclamation-triangle-fill"></i>
                        <?php echo $error . "<br>";
                    } ?>
                </div>
            <?php } ?>
            
            <div id="current-projects-col" class="row mx-3 collapse show" style="height: 100%;">
                <h3 class="mx-auto text-center border-bottom w-50">Tableaux Actifs</h3>
                <div style="height: 90%; overflow: auto">
                    <?php 
                    if(count($Projects) > 0)
                    {
                        foreach($Projects as $Project)
                        {
                        ?>
                        <div class="pb-3 mb-5 border-lg" style="height:max-content;">
                            <div class="row text-center justify-content-around">
                                <div class="col-10 sticker mt-3 pt-3">
                                    <p><b>Nom du projet : </b><?= $Project->getName() ?></p>
                                </div>
                            </div>
                            <div class="row text-center justify-content-around">
                                <div class="col-10 sticker mt-4 pt-3">
                                    <p><b>Equipe : </b><?= $Project->teamName ?></p>
                                </div>
                            </div>
                            <div class="row mt-4 justify-content-around">
                                <div class="sticker col-4 text-center">
                                    <b>Nb membres</b>
                                    <p class="text-center"><?= $Project->membersCount ?></p>
                                    <br>
                                </div>
                                
                                <div class="sticker col-4 text-center">
                                    <b>Nb tâches</b>
                                    <br>
                                    <p class="text-center"><?= $Project->tasksCount ?></p>
                                </div>
                            </div>
                            <div class="row">
                                <a href="<?= CONTROLLERS_URL ?>membres/map.php?projectId=<?= $project->rowid ?>" class="btn btn-outline-info w-25 mt-3 mx-auto">Aller sur le tableau</a>
                            </div>
                        </div>
                        <?php 
                        }
                    }
                    else
                    { ?>
                        <div class="sticker-deep mx-auto mt-5 pt-3 text-center" style="width: 70%; height: 30%">
                            <h3>Vous n'avez encore aucun tableau en cours.</h3>
                        </div>
                    <?php
                    } ?>

                </div>
            </div>

            <div id="account-delete-confirmation" class="sticker collapse text-center mx-auto h-100 pt-3">
                <h3 class="mx-auto border-bottom w-75">Confirmation de suppression de compte</h3>

                <p class="mt-5"><b><span style="color: red;">Êtes-vous sûr de vouloir supprimer votre compte ? (cette action est irréversible)</span></b></p>
            
                <a href="<?= CONTROLLERS_URL ?>membres/tableauDeBord.php?action=accountDelete" class="btn btn-outline-danger w-50 mt-5">Supprimer</a>
                <a id="cancel-account-deletion" class="btn btn-outline-warning w-50 mt-3">Annuler</a>
            </div>
        </div> 

        <!-- user properties -->
        <div class="col-3 profile-section mt-3 position-relative" style="height:87%">

            <h3 class="mx-auto mt-3 text-center" style="border-bottom: black solid 1px; border-color: rgb(216, 214, 214); width: 80%">Profil</h3>

            <div class="d-flex justify-content-center mt-4">
                <div class="text-center w-75">
                    <form action="<?= CONTROLLERS_URL ?>membres/tableauDeBord.php?action=userUpdate" method="POST">

                        <input type="text" name="lastname" class="sticker form-control pt-2 text-center" value="<?= $CurrentUser->lastname ?>">
                        <input type="text" name="firstname" class="sticker form-control mt-4 pt-2 text-center" value="<?= $CurrentUser->firstname ?>">
                        <input type="email" name="email" class="sticker form-control mt-4 pt-2 text-center" value="<?= $CurrentUser->email ?>">
    
                        <button type="submit" class="w-50 mt-4 pt-2 btn btn-outline-primary text-center">Mettre à jour</button>
                    </form>

                    <div class="text-center position-absolute bottom-0 start-50 translate-middle-x">
                        <a class="btn btn-outline-secondary mt-5 w-100" href="<?= CONTROLLERS_URL ?>membres/passwordUpdate.php">Modifier mot de passe</a>
                        <button id="delete-account-btn" class="btn btn-outline-danger mt-3 mb-3 w-100">Supprimer le compte</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="<?= JS_URL ?>membres/tableauDeBord.js" type="text/Javascript"></script>
<?php
require_once "layouts/pied.php" ?>