<?php 
require_once "layouts/entete.php";
?>
<div class="col-10 pt-3">
    <div class="row">
        <div class="col-8 position-relative">
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
            <div id="delete-organization-div" class="sticker py-3 px-3 text-center collapse" style="height: max-content;">
                <b>Êtes-vous sûr de vouloir supprimer l'organisation ?</b><br>
                (Cette action est définitive et supprimera toute donnée étant en lien avec celle-ci)
                <div class="mt-5 row">
                    <div class="col-6 text-end">
                        <a class="btn btn-outline-danger w-50" href="<?= CONTROLLERS_URL ?>admin/gestionOrganisation.php?action=deleteOrganization">Oui</a>
                    </div>
                    <div class="col-6 text-start">
                        <button id="cancel-delete-btn" class="btn btn-outline-warning w-50">Non</button>
                    </div>
                </div>
            </div>

            <div id="password-update-form" class="mx-auto sticker w-75 mt-5 pb-3 collapse" style="height: max-content;">
                <h3 class="text-center mx-auto border-bottom w-75 mt-3">Modification de mot de passe</h3>

                <form class="pt-4" action="<?= CONTROLLERS_URL ?>admin/gestionOrganisation.php?action=updatePassword" method="POST">
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

                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-outline-primary w-50" name="envoi" value="1">Valider</button>
                    </div>
                    <div class="text-center mt-3">
                        <button id="cancel-password-update" class="btn btn-outline-danger w-50">Annuler</button>
                    </div>
                </form>

            </div>

            <div id="email-update-form" class="mx-auto sticker w-75 mt-5 pb-3 collapse <?= in_array('email', $invalidForm) ? 'show' : '' ?>" style="height: max-content;">
                <h3 class="text-center mx-auto border-bottom w-75 mt-3">Modification de l'adresse email</h3>

                <form class="pt-4" action="<?= CONTROLLERS_URL ?>admin/gestionOrganisation.php?action=updateEmail" method="POST">
                    <div class="form-floating mb-3 w-75 mx-auto">
                        <input class="form-control <?= in_array('email', $invalidInput) ? 'is-invalid' : '' ?>" type="email" name="email" id="email" placeholder="Nouvelle adresse email" value="<?= $email ?? ""?>"  required>
                        <label for="email">Nouvelle adresse email</label>
                    </div>

                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-outline-primary w-50" name="envoi" value="1">Valider</button>
                    </div>
                    <div class="text-center mt-3">
                        <button id="cancel-email-update" class="btn btn-outline-danger w-50">Annuler</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-4 pe-5" >
            <div class="card" style="height: 88vh;">
                <div class="card-header">
                    <h4 class="text-center">Informations sur l'organisation</h4>
                </div>
                <div class="card-body position-relative">
                    <h3 class="text-center border-bottom w-75 mx-auto"><?= $CurrentOrganization->name ?></h3>
                    <h5 class="text-center mx-auto w-50 border-bottom mt-3">Email</h5>
                    <div class="d-flex justify-content-center mt-3">
                        <button id="email-info-btn" type="text" class="btn btn-outline-classic form-control w-75 mx-auto text-center"><?= $CurrentOrganization->email ?></button>
                    </div>

                    <h5 class="mt-3 text-center mx-auto w-50 border-bottom">Membres</h5>
                    <div class="d-flex justify-content-center mt-3">
                        <button type="text" class="btn btn-outline-classic form-control w-75 mx-auto text-center"><?= $CurrentOrganization->membersCount ?></button>
                    </div>

                    <h5 class="mt-3 text-center mx-auto w-50 border-bottom">Projets en cours</h5>
                    <div class="d-flex justify-content-center mt-3">
                        <button type="text" class="btn btn-outline-classic form-control w-75 mx-auto text-center"><?= $CurrentOrganization->projectsCount ?></button>
                    </div>

                    <h5 class="mt-3 text-center mx-auto w-50 border-bottom">Projets terminés</h5>
                    <div class="d-flex justify-content-center mt-3">
                        <button type="text" class="btn btn-outline-classic form-control w-75 mx-auto text-center"><?= $CurrentOrganization->archivedProjectsCount ?></button>
                    </div>

                    <div class="w-100 mb-3 position-absolute bottom-0 start-50 translate-middle-x text-center">
                        <button id="password-update-btn" class="btn btn-outline-primary w-75 mt-2">Modifier le mot de passe</button>
                        <button id="delete-organization-button" class="btn btn-outline-danger w-75 mt-3">Supprimer l'organisation</button>
                    </div>
                </div>
            </div>
        </div>

    </div>

<script type="text/Javascript" src="<?= JS_URL ?>admin/gestionOrganisation.js"></script>

<?php
require_once "layouts/pied.php";
