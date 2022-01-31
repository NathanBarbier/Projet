<?php
require_once "layouts/entete.php";
?>
    <?php if($success) { ?>
    <div class="alert alert-success mt-3 w-50 text-center position-absolute top-0 start-50 translate-middle-x">
        <i class="bi bi-check-circle-fill"></i>
        <?= $success; ?>
    </div>
    <?php } else if($errors) { ?>
    <div class="alert alert-danger mt-3 w-50 text-center position-absolute top-0 start-50 translate-middle-x">
    <?php foreach($errors as $error) { ?>
        <i class="bi bi-exclamation-triangle-fill"></i>
        <?php echo $error . "<br>";
    } ?>
    </div>
    <?php } ?>

    <table class="table mt-4">
        <thead>
            <tr>
                <th colspan="7">
                    <div style="float : left"><strong>Liste des membres</strong></div>
                    <div class="me-5" style="float : right">
                        <div>Nombres de membres : <?= count($Organization->getUsers()); ?></div> 
                    </div>
                </th>
            </tr>
        </thead>
        <tbody>
            <tr class="text-center">
                <th>NOM</th>
                <th>Prénom</th>
                <th>Adresse email</th>
                <th>Droits</th>
                <th>Options</th>
            </tr>

            <?php
            foreach($Organization->getUsers() as $User)
            {
            ?>
            <tr class="text-center">
                <td>
                    <div id="nom<?= $User->getRowid() ?>" class="collapse show">
                        <?= $User->getLastname(); ?>
                    </div>

                    <div id="divModifNom<?= $User->getRowid() ?>" class="collapse show mt-3">
                        <a onclick="afficherConfModifNom(<?= $User->getRowid() ?>)" class="btn btn-outline-info">Modifier</a>
                    </div>
                    <form method="POST" action="<?= CONTROLLERS_URL ?>admin/listeMembres.php?action=updateLastname&idUser=<?= $User->getRowid() ?>">
                        <div id="divInputModifNom<?= $User->getRowid() ?>" class="collapse">
                            <input class="form-control mb-1 text-center w-50 mx-auto" value="<?= $User->getLastname() ?>" type="text" name="lastname" placeholder="Écrivez un nom" required>
                        </div>

                        <!-- Confirmer la modification du nom de l'utilisateur -->
                        <div id="divConfModifNom<?= $User->getRowid() ?>" class="collapse">
                            <button type="submit" name="envoi" value="<?= true ?>" class="btn btn-success">Confirmer</button>
                            <a onclick="annulerModifNom(<?= $User->getRowid() ?>)" class="btn btn-warning">Annuler</a>
                        </div>
                    </form>
                </td>
                
                <td>
                    <div id="prenom<?= $User->getRowid() ?>" class="collapse show">
                        <?=$User->getFirstname();?>
                    </div>

                    <div id="divModifPrenom<?= $User->getRowid() ?>" class="collapse show mt-3">
                        <a onclick="afficherConfModifPrenom(<?= $User->getRowid() ?>)" class="btn btn-outline-info">Modifier</a>
                    </div>
                    <form method="POST" action="<?= CONTROLLERS_URL ?>admin/listeMembres.php?action=updateFirstname&idUser=<?= $User->getRowid() ?>">
                        <div id="divInputModifPrenom<?= $User->getRowid() ?>" class="collapse">
                            <input class="form-control mb-1 text-center w-50 mx-auto" value="<?= $User->getFirstname() ?>" type="text" name="firstname" placeholder="Écrivez un prénom" required>
                        </div>

                        <!-- Confirmer la modification du nom de l'utilisateur -->
                        <div id="divConfModifPrenom<?= $User->getRowid() ?>" class="collapse">
                            <button type="submit" name="envoi" value="<?= true ?>" class="btn btn-success">Confirmer</button>
                            <a onclick="annulerModifPrenom(<?= $User->getRowid() ?>)" class="btn btn-warning">Annuler</a>
                        </div>
                    </form>
                </td>

                <td class="align-middle"><?= $User->getEmail();?></td>

                <td class="align-middle"><b><?= $User->isAdmin() == 1 ? '<span style="color:red">Administrateur</span>' : '<span style="color:grey">Utilisateur</span>' ?></b></td>

                <td class="align-middle">
                    <div id="divDelUser<?= $User->getRowid() ?>" class="collapse show">
                        <button onclick="afficherConfDelUser(<?= $User->getRowid() ?>)" class="btn btn-outline-danger">Supprimer</button>
                    </div>
                    <div id="divConfDelUser<?= $User->getRowid() ?>" class="collapse">
                        <div class="row">
                            <div class="col-sm-12 mb-2 col-md-6 text-md-end">
                                <a href="<?= CONTROLLERS_URL ?>admin/listeMembres.php?action=deleteUser&idUser=<?= $User->getRowid() ?>" class="btn btn-outline-danger w-75">Confirmer</a><br>
                            </div>
                            <div class="col-sm-12 col-md-6 text-md-start">
                                <button onclick="annulerDelUser(<?= $User->getRowid() ?>)" class="btn btn-outline-warning w-75">Annuler</button>
                            </div>
                        </div class="row">
                    </div>
                </td>
            </tr>
            <?php
            }

            ?>
        </tbody>

    </table>

<script src="<?= JS_URL ?>admin/listeMembres.js"></script>

<?php
require_once "layouts/pied.php";
?>