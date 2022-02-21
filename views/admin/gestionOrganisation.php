<?php 
require_once "layouts/entete.php";
?>
    <div class="row ps-4 px-2" style="height: 95vh;">
        <?php if($errors) { ?>
        <div class="alert alert-danger w-50 text-center position-absolute mt-3 top-0 start-50 translate-middle-x before">
        <?php foreach($errors as $error) { ?>
            <i class="bi bi-exclamation-triangle-fill"></i>
            <?php echo $error . "<br>";
        } ?>
        </div>
        <?php } else if($success) { ?>
        <div class="alert alert-success w-50 text-center position-absolute mt-3 top-0 start-50 translate-middle-x before">
            <i class="bi bi-check-circle-fill"></i>
            <?= $success ?>
        </div>    
        <?php } ?>
        <div id="gestion-organisation-left-section" class="col-sm-4 px-3 col-md-4 col-lg-4 col-xl-6 h-100 position-relative overflow-y">
            <div id="delete-organization-div" class="sticker mb-3 py-3 px-3 text-center collapse" style="height: max-content;">
                <h4 class="mx-auto border-bottom w-75 mb-3">Confirmation de suppression d'organisation</h4>
                <b>Êtes-vous sûr de vouloir supprimer l'organisation ?</b>
                <br>
                <span style='color:red'>(Cette action est irréversible)</span>
                <div class="mt-4 row">
                    <div class="col-6 col-sm-12 mb-0 mb-sm-2 mb-md-0 col-md-6">
                        <a class="w-100 custom-button danger pt-2 double-button-responsive" href="<?= CONTROLLERS_URL ?>admin/gestionOrganisation.php?action=deleteOrganization">Supprimer</a>
                    </div>
                    <div class="col-6 col-sm-12 col-md-6">
                        <button id="cancel-delete-btn" class="w-100 custom-button warning double-button-responsive">Annuler</button>
                    </div>
                </div>
            </div>

            <div id="password-update-form" class="mx-auto sticker mb-3 py-3 px-3 pb-3 collapse" style="height: max-content;">
                <h4 class="text-center mx-auto border-bottom w-75">Modification de mot de passe</h4>

                <form class="pt-3" action="<?= CONTROLLERS_URL ?>admin/gestionOrganisation.php?action=updatePassword" method="POST">
                    <div class="form-floating mb-3 w-75 mx-auto">
                        <input class="form-control" type="password" name="oldpwd" id="oldpwd" placeholder="Ancien mot de passe" value="<?= $oldPwd ?? ""?>"  required>
                        <label for="prenom">Ancien mot de passe</label>
                    </div>
    
                    <div class="form-floating mb-3 w-75 mx-auto">
                        <input class="form-control" type="password" name="newpwd" id="newpwd" placeholder="Nouveau mot de passe" value="<?= $newPwd ?? ""?>"  required>
                        <label for="prenom">Nouveau mot de passe</label>
                    </div>
    
                    <div class="form-floating mb-3 w-75 mx-auto">
                        <input class="form-control" type="password" name="newpwd2" id="newpwd2" placeholder="Confirmer le mot de passe" value="<?= $newPwd2 ?? ""?>"  required>
                        <label for="prenom">Confirmer le mot de passe</label>
                    </div>

                    <div class="mt-4 row">
                        <div class="col-6 col-sm-12 mb-0 mb-sm-2 mb-md-0 col-md-6">
                            <button type="submit" class="w-100 custom-button double-button-responsive" name="envoi" value="1">Valider</button>
                        </div>
                        <div class="col-6 col-sm-12 col-md-6">        
                            <button id="cancel-password-update" class="w-100 text-light custom-button danger double-button-responsive">Annuler</button>
                        </div>
                    </div>
                </form>
            </div>

            <div id="account-delete-confirmation" class="sticker collapse text-center mx-auto mb-3 pt-3" style="height: max-content;">
                <h4 class="mx-auto border-bottom w-75">Confirmation de suppression de compte</h4>

                <p class="mt-3 mx-3"><b>Êtes-vous sûr de vouloir supprimer votre compte ?</b>
                <br>
                (Cette action est définitive et supprimera toute donnée étant en lien avec celui-ci)</p>
            
                <div class="mt-4 pb-3 row px-3">
                    <div class="col-6 col-sm-12 mb-0 mb-sm-2 mb-md-0 col-md-6">
                        <a href="<?= CONTROLLERS_URL ?>membre/tableauDeBord.php?action=accountDelete" class="w-100 text-light pt-2 custom-button danger double-button-responsive">Supprimer</a>
                    </div>
                    <div class="col-6 col-sm-12 col-md-6">
                        <a id="cancel-account-deletion" class="w-100 custom-button warning pt-2 double-button-responsive">Annuler</a>
                    </div>
                </div>
            </div>

            <div id="email-update-form" class="mx-auto sticker w-100 mb-3 pb-3 collapse <?= in_array('email', $invalidForm) ? 'show' : '' ?>" style="height: max-content;">
                <h4 class="text-center mx-auto border-bottom w-75 mt-3">Édition de l'adresse email</h4>
                <form class="pt-4" action="<?= CONTROLLERS_URL ?>admin/gestionOrganisation.php?action=updateEmail" method="POST">
                    <div class="form-floating mb-3 w-75 mx-auto">
                        <input class="form-control <?= in_array('email', $invalidInput) ? 'is-invalid' : '' ?>" type="email" name="email" id="email" placeholder="Nouvelle adresse email" value="<?= $email ?? ""?>"  required>
                        <label for="email">Adresse email</label>
                    </div>

                    <div class="mt-4 row mx-3">
                        <div class="col-6">
                            <button type="submit" class="w-100 custom-button double-button-responsive" name="envoi" value="1">Valider</button>
                        </div>
                        <div class="col-6">
                            <a id="cancel-email-update" class="w-100 text-light text-center pt-2 custom-button danger double-button-responsive">Annuler</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-sm-4 col-md-4 col-lg-4 col-xl-3 px-3 mb-3 h-100">
            <div class="card" style="height: 100%;">
                <div class="card-header">
                <h3 class="text-center border-bottom w-75 mx-auto"><?= $Organization->getName() ?></h3>
                </div>
                <div class="card-body position-relative">
                    <h6 class="mt-2 text-center mx-auto w-50 border-bottom">Membres</h6>
                    <div class="d-flex justify-content-center">
                        <button type="text" class="btn btn-outline-classic form-control w-75 mx-auto text-center"><?= count($Organization->getUsers()) ?></button>
                    </div>

                    <h6 class="mt-2 text-center mx-auto w-50 border-bottom">Projets en cours</h6>
                    <div class="d-flex justify-content-center">
                        <button type="text" class="btn btn-outline-classic form-control w-75 mx-auto text-center">
                            <?php 
                            $counter = 0; 
                            foreach($Organization->getProjects() as $Project) {
                                if($Project->isActive()) {
                                    $counter++;
                                }
                            } 
                            echo $counter;
                            ?>
                        </button>
                    </div>

                    <h6 class="mt-2 text-center mx-auto w-50 border-bottom">Projets terminés</h6>
                    <div class="d-flex justify-content-center">
                        <button type="text" class="btn btn-outline-classic form-control w-75 mx-auto text-center">
                            <?php 
                            $counter = 0; 
                            foreach($Organization->getProjects() as $Project) {
                                if(!$Project->isActive()) {
                                    $counter++;
                                }
                            } 
                            echo $counter;
                            ?>
                        </button>
                    </div>

                    <div class="w-100 mt-3 mb-3 mx-auto text-center">
                        <a href="#delete-organization-div" id="delete-organization-button" class="text-light pt-2 custom-button danger btn-sm w-100 mt-3 px-1">Supprimer l'organisation</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-4 col-md-4 col-lg-4 col-xl-3 px-3 mb-3 h-100">
            <!-- user profile-->
            <div class="card" style="height: 100%">
                <div class="card-header">
                    <h3 class="mx-auto text-center" style="border-bottom: black solid 1px; border-color: rgb(216, 214, 214); width: 80%">Profil</h3>
                </div>
                
                <h6 class="border-bottom mx-auto w-50 text-center mt-2">Email</h6>
                <div class="text-center">
                    <a href="#email-update-form" id="email-info-btn" type="text" class="custom-button secondary pt-2 form-control w-75 mx-auto text-center" tabindex="0" data-bs-toggle="tooltip" title="Modifier l'adresse email"><?= $User->getEmail() ?></a>
                </div>

                <div class="text-center mx-auto w-100 mt-2" style="height: 100%;">
                    <form id="profile-form" class="w-75 mx-auto" action="<?= CONTROLLERS_URL ?>admin/gestionOrganisation.php?action=userUpdate" method="POST" style="height: 60%;">
                        
                        <h6 class="border-bottom mx-auto w-50">Nom</h6>
                        <input type="text" name="lastname" class="sticker form-control pt-2 text-center" value="<?= $User->getLastname() ?>">
                        <h6 class="border-bottom mx-auto w-50 mt-2">Prénom</h6>
                        <input type="text" name="firstname" class="sticker form-control pt-2 text-center" value="<?= $User->getFirstname() ?>">
                        <h6 class="border-bottom mx-auto w-50 mt-2">Email</h6>
                        <input type="email" name="email" class="sticker form-control pt-2 text-center" value="<?= $User->getEmail() ?>">
                        
                    </form>
                    
                    <div class="overflow-y w-100 px-3" style="height: 40%;">
                        <button id="update-profile-submit" class="w-100 mt-4 custom-button btn-sm text-center">Mettre à jour</button>
                        <a href="#password-update-form" class="custom-button secondary btn-sm mt-2 w-100 px-1 pt-2" id="password-update-btn">Éditer mot de passe</a>
                        <a href="#account-delete-confirmation" id="delete-account-btn" class="text-light pt-2 custom-button danger btn-sm mt-2 mb-3 w-100 px-1">Supprimer le compte</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

<script type="text/Javascript" src="<?= JS_URL ?>admin/gestionOrganisation.min.js" defer></script>

<?php
require_once "layouts/pied.php";
