<?php 
require_once "layouts/header.php";
?>

<div class="col-12" style="height: 100%;">
    <div class="row w-100 mx-auto px-3" style="height: 100%;">
        <div class="col-sm-12 col-md-7 col-lg-8 mt-3 position-relative" style="height:87%; height:max-content">
            
            <div id="current-projects-col" class="bg-white border row mx-3 collapse show mb-3" style="border-radius: 15px;">
                <h3 class="mx-auto text-center w-50 py-2 underline">Tableaux Actifs</h3>
                <div class="pb-5" style="height: 90%; overflow: auto">
                    <?php 
                    if(count($Projects) > 0) 
                    {
                        foreach($Projects as $Project) 
                        {
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
                                        <?php 
                                        // for this project
                                        // get the linked team to the user
                                        if(!empty($Teams[$Project->getRowid()]))
                                        {
                                            echo $Teams[$Project->getRowid()]->getName();
                                        } ?>
                                    </p>
                                </div>
                            </div>
                            <div class="row mt-4 justify-content-around">
                                <div class="sticker col-4 pt-3 text-center">
                                    <b class="border-bottom">Membres</b>
                                    <p class="text-center">
                                        <?php // count team members
                                        if(!empty($Teams[$Project->getRowid()]))
                                        {
                                            $teamId = $Teams[$Project->getRowid()]->getRowid();
                                            echo $BelongsToRepository->fetchTeamMembersCount($teamId);
                                        }
                                        ?>
                                    </p>
                                    <br>
                                </div>
                                
                                <div class="sticker col-4 pt-3 text-center">
                                    <b class="border-bottom">Tâches affectées</b>
                                    <br>
                                    <p class="text-center">
                                    <?php 
                                    $TeamRepository = new TeamRepository();
                                    $affectedTasksCount = $TeamRepository->fetchAffectedTasksCount($teamId, $idUser);
                                    echo $affectedTasksCount;
                                    ?>
                                    </p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-8 col-sm-6 col-md-8 col-lg-6 col-xl-4 mx-auto">
                                    <a href="<?= CONTROLLERS_URL ?>member/map.php?projectId=<?= $Project->getRowid() ?>&teamId=<?= $teamId ?>" class="custom-button info pt-2 w-100 mt-3 mx-1 text-center mx-auto">
                                        Aller sur le tableau
                                    </a>
                                </div>
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
            
                <a href="<?= CONTROLLERS_URL ?>member/dashboard.php?action=accountDelete" class="custom-button danger w-50 pt-2 mt-4">Supprimer</a>
                <a id="cancel-account-deletion" class="custom-button warning w-50 pt-2 mt-3 mb-3">Annuler</a>
            </div>
        </div> 

        <!-- user properties -->
        <div class="col-sm-12 col-md-5 bg-white col-lg-4 profile-section mt-3 position-relative" style="height:87%">

            <h3 class="mx-auto mt-3 text-center underline" style="width: 80%">Profil</h3>
            <hr class="w-75 mx-auto">

            <div class="d-flex justify-content-center mt-4">
                <div class="text-center w-75">
                    <form action="<?= CONTROLLERS_URL ?>member/dashboard.php?action=userUpdate" method="POST">

                        <h6 class="border-bottom mx-auto w-50">Nom</h6>
                        <input type="text" name="lastname" class="sticker form-control pt-2 text-center h-50px" value="<?= $User->getLastname() ?>">
                        
                        <h6 class="border-bottom mx-auto w-50">Prénom</h6>
                        <input type="text" name="firstname" class="sticker form-control mt-4 pt-2 text-center h-50px" value="<?= $User->getFirstname() ?>">
                        
                        <h6 class="border-bottom mx-auto w-50">Email</h6>
                        <input type="email" name="email" class="sticker form-control mt-4 pt-2 text-center h-50px" value="<?= $User->getEmail() ?>">
    
                        <button type="submit" class="w-75 mt-4 custom-button text-center px-1">Mettre à jour</button>
                    </form>

                    <div class="text-center position-absolute bottom-0 start-50 translate-middle-x w-100">
                        <a class="custom-button secondary mt-5 pt-2 px-1 w-75" href="<?= CONTROLLERS_URL ?>member/passwordUpdate.php">Modifier mot de passe</a>
                        <button id="delete-account-btn" class="custom-button danger mt-3 mb-3 px-1 w-75">Supprimer le compte</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="<?= JS_URL ?>member/dashboard.min.js" type="text/Javascript" defer></script>
<?php
require_once "layouts/footer.php" ?>