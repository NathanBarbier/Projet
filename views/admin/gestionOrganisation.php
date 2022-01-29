<?php 
require_once "layouts/entete.php";
?>
    <div class="row ps-4 px-2">
        <div class="col-sm-4 px-3 col-md-4 col-lg-6 position-relative">
            <?php if($errors) { ?>
            <div class="alert alert-danger w-50 text-center position-absolute top-0 start-50 translate-middle-x">
            <?php foreach($errors as $error) { ?>
                <i class="bi bi-exclamation-triangle-fill"></i>
                <?php echo $error . "<br>";
            } ?>
            </div>
            <?php } else if($success) { ?>
            <div class="alert alert-success w-50 text-center position-absolute top-0 start-50 translate-middle-x">
                <i class="bi bi-check-circle-fill"></i>
                <?= $success ?>
            </div>    
            <?php } ?>
            <div id="delete-organization-div" class="sticker mb-3 py-3 px-3 text-center collapse" style="height: max-content;">
                <h4 class="mx-auto border-bottom w-75 mb-3">Confirmation de suppression d'organisation</h4>
                <b>Êtes-vous sûr de vouloir supprimer l'organisation ?</b>
                <br>
                (Cette action est définitive et supprimera toute donnée étant en lien avec celle-ci)
                <div class="mt-4 row">
                    <div class="col-6 text-end">
                        <a class="btn btn-outline-danger double-button-responsive" href="<?= CONTROLLERS_URL ?>admin/gestionOrganisation.php?action=deleteOrganization">Supprimer</a>
                    </div>
                    <div class="col-6 text-start">
                        <button id="cancel-delete-btn" class="btn btn-outline-warning double-button-responsive">Annuler</button>
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
                        <div class="col-6 text-end">
                            <button type="submit" class="btn btn-outline-primary double-button-responsive" name="envoi" value="1">Valider</button>
                        </div>
                        <div class="col-6 text-start">        
                            <button id="cancel-password-update" class="btn btn-outline-danger double-button-responsive">Annuler</button>
                        </div>
                    </div>
                </form>
            </div>

            <div id="account-delete-confirmation" class="sticker collapse text-center mx-auto pt-3" style="height: max-content;">
                <h4 class="mx-auto border-bottom w-75">Confirmation de suppression de compte</h4>

                <p class="mt-3 mx-3"><b>Êtes-vous sûr de vouloir supprimer votre compte ?</b>
                <br>
                (Cette action est définitive et supprimera toute donnée étant en lien avec celui-ci)</p>
            
                <div class="mt-4 pb-3 row">
                    <div class="col-6 text-end">
                        <a href="<?= CONTROLLERS_URL ?>membre/tableauDeBord.php?action=accountDelete" class="btn btn-outline-danger double-button-responsive">Supprimer</a>
                    </div>
                    <div class="col-6 text-start">
                        <a id="cancel-account-deletion" class="btn btn-outline-warning double-button-responsive">Annuler</a>
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

                    <div class="mt-4 row">
                        <div class="col-6 text-end">
                            <button type="submit" class="btn btn-outline-primary double-button-responsive" name="envoi" value="1">Valider</button>
                        </div>
                        <div class="col-6 text-start">
                            <a id="cancel-email-update" class="btn btn-outline-danger double-button-responsive">Annuler</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-sm-4 col-md-4 col-lg-3 px-3 mb-3 overflow-y">
            <div class="card" style="height: 88vh;">
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
                        <button type="text" class="btn btn-outline-classic form-control w-75 mx-auto text-center"><?= count($Organization->getProjects()) ?></button>
                    </div>

                    <h6 class="mt-2 text-center mx-auto w-50 border-bottom">Projets terminés</h6>
                    <div class="d-flex justify-content-center">
                        <button type="text" class="btn btn-outline-classic form-control w-75 mx-auto text-center"><?= $Organization->countArchivedProjects() ?></button>
                    </div>

                    <div class="w-100 mt-3 mb-3 mx-auto text-center">
                        <button id="delete-organization-button" class="btn btn-outline-danger btn-sm w-75 mt-3">Supprimer l'organisation</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-4 col-md-4 col-lg-3 px-3 mb-3 overflow-y">
            <!-- user profile-->
            <div class="card" style="height: 88vh;">
                <div class="card-header">
                    <h3 class="mx-auto text-center" style="border-bottom: black solid 1px; border-color: rgb(216, 214, 214); width: 80%">Profil</h3>
                </div>
                
                <h6 class="border-bottom mx-auto w-50 text-center mt-2">Email</h6>
                <div class="d-flex justify-content-center">
                    <button id="email-info-btn" type="text" class="btn btn-outline-classic form-control w-75 mx-auto text-center" tabindex="0" data-bs-toggle="tooltip" title="Modifier l'adresse email"><?= $User->getEmail() ?></button>
                </div>

                <div class="d-flex justify-content-center mt-2">
                    <div class="text-center w-75">
                        <form action="<?= CONTROLLERS_URL ?>admin/gestionOrganisation.php?action=userUpdate" method="POST">
                            
                            <h6 class="border-bottom mx-auto w-50">Nom</h6>
                            <input type="text" name="lastname" class="sticker form-control pt-2 text-center" value="<?= $User->getLastname() ?>">
                            <h6 class="border-bottom mx-auto w-50 mt-2">Prénom</h6>
                            <input type="text" name="firstname" class="sticker form-control pt-2 text-center" value="<?= $User->getFirstname() ?>">
                            <h6 class="border-bottom mx-auto w-50 mt-2">Email</h6>
                            <input type="email" name="email" class="sticker form-control pt-2 text-center" value="<?= $User->getEmail() ?>">
                            
                            <button type="submit" class="w-75 mt-4 btn btn-outline-primary btn-sm text-center">Mettre à jour</button>
                        </form>
                        
                        <a href="#" class="btn btn-outline-secondary btn-sm mt-2 w-75" id="password-update-btn">Éditer mot de passe</a>
                        <button id="delete-account-btn" class="btn btn-outline-danger btn-sm mt-2 mb-3 w-75">Supprimer le compte</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

<script type="text/Javascript" src="<?= JS_URL ?>admin/gestionOrganisation.js"></script>

<?php
require_once "layouts/pied.php";
