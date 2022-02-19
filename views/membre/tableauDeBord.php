<?php 
require_once "layouts/entete.php";
?>

<div class="col-12" style="height: 100%;">
    <div class="row w-100 mx-auto px-3" style="height: 100%;">
        <div class="col-sm-12 col-md-7 col-lg-8 mt-3 position-relative" style="height:87%; height:max-content">
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
            
            <div id="current-projects-col" class="bg-white border row mx-3 collapse show mb-3" style="border-radius: 15px;">
                <h3 class="mx-auto text-center w-50 py-2 underline">Tableaux Actifs</h3>
                <div class="pb-5" style="height: 90%; overflow: auto">
                    <?php 
                    if(count($Projects) > 0) {
                        foreach($Projects as $Project) { 
                        $teamId = 0; ?>
                        <div class="pb-3 mb-5 border-lg" style="height: max-content;">
                            <div class="row text-center justify-content-around">
                                <div class="col-10 sticker mt-3 pt-3">
                                    <p><b>Nom du projet : </b><?= $Project->getName() ?></p>
                                </div>
                            </div>
                            <div class="row text-center justify-content-around">
                                <div class="col-10 sticker mt-4 pt-3">
                                    <p>
                                        <b>Equipe : </b>
                                        <?php foreach($Project->getTeams() as $Team) {
                                            foreach($Team->getUsers() as $User) {
                                                if($User->getRowid() == $idUser) {
                                                    echo $Team->getName();
                                                    break 2;
                                                }
                                            } ?>
                                        <?php } ?>
                                    </p>
                                </div>
                            </div>
                            <div class="row mt-4 justify-content-around">
                                <div class="sticker col-4 text-center">
                                    <b>Nb membres</b>
                                    <p class="text-center">
                                        <?php
                                        $counter = 0; 
                                        foreach($Project->getTeams() as $Team) {
                                            $counter += count($Team->getUsers());
                                        } 
                                        echo $counter; ?>
                                    </p>
                                    <br>
                                </div>
                                
                                <div class="sticker col-4 text-center">
                                    <b>Nb tâches</b>
                                    <br>
                                    <p class="text-center">
                                    <?php foreach($Project->getTeams() as $Team) {
                                            foreach($Team->getUsers() as $User) {
                                                if($User->getRowid() == $idUser) {
                                                    $teamId = $Team->getRowid();
                                                    $taskCount = 0;
                                                    foreach($Team->getMapColumns() as $MapColumn) {
                                                        $taskCount+= count($MapColumn->getTasks());
                                                    }
                                                    break 2;
                                                }
                                            } ?>
                                        <?php } ?>
                                        <?= $taskCount ?>
                                    </p>
                                </div>
                            </div>
                            <div class="row">
                                <a href="<?= CONTROLLERS_URL ?>membre/map.php?projectId=<?= $Project->getRowid() ?>&teamId=<?= $teamId ?>" class="btn btn-outline-info w-25 mt-3 mx-auto">Aller sur le tableau</a>
                            </div>
                        </div>
                        <?php }
                    } else { ?>
                        <div class="sticker-deep mx-auto mt-5 pt-3 text-center" style="width: 80%; height: 30vh">
                            <h3>Vous n'avez encore aucun tableau en cours.</h3>
                        </div>
                    <?php } ?>

                </div>
            </div>

            <div id="account-delete-confirmation" class="sticker collapse text-center mx-auto h-100 pt-3">
                <h3 class="mx-auto border-bottom w-75">Confirmation de suppression de compte</h3>

                <p class="mt-3"><b><span style="color: red;">Êtes-vous sûr de vouloir supprimer votre compte ? (cette action est irréversible)</span></b></p>
            
                <a href="<?= CONTROLLERS_URL ?>membre/tableauDeBord.php?action=accountDelete" class="btn btn-outline-danger w-50 mt-4">Supprimer</a>
                <a id="cancel-account-deletion" class="btn btn-outline-warning w-50 mt-3 mb-3">Annuler</a>
            </div>
        </div> 

        <!-- user properties -->
        <div class="col-sm-12 col-md-5 bg-white col-lg-4 profile-section mt-3 position-relative" style="height:87%">

            <h3 class="mx-auto mt-3 text-center underline" style="width: 80%">Profil</h3>
            <hr class="w-75 mx-auto">

            <div class="d-flex justify-content-center mt-4">
                <div class="text-center w-75">
                    <form action="<?= CONTROLLERS_URL ?>membre/tableauDeBord.php?action=userUpdate" method="POST">

                        <input type="text" name="lastname" class="sticker form-control pt-2 text-center" value="<?= $User->getLastname() ?>">
                        <input type="text" name="firstname" class="sticker form-control mt-4 pt-2 text-center" value="<?= $User->getFirstname() ?>">
                        <input type="email" name="email" class="sticker form-control mt-4 pt-2 text-center" value="<?= $User->getEmail() ?>">
    
                        <button type="submit" class="w-50 mt-4 pt-2 btn btn-outline-primary text-center">Mettre à jour</button>
                    </form>

                    <div class="text-center position-absolute bottom-0 start-50 translate-middle-x">
                        <a class="btn btn-outline-secondary mt-5 w-100" href="<?= CONTROLLERS_URL ?>membre/passwordUpdate.php">Modifier mot de passe</a>
                        <button id="delete-account-btn" class="btn btn-outline-danger mt-3 mb-3 w-100">Supprimer le compte</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="<?= JS_URL ?>membre/tableauDeBord.min.js" type="text/Javascript" defer></script>
<?php
require_once "layouts/pied.php" ?>