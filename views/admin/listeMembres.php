<?php
require_once "layouts/entete.php";
?>

<div class="col-8 offset-1 position-relative">
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

    <table class="table">
        <thead>
            <tr>
                <th colspan="7">
                    <div style="float : left"><strong>Liste des membres</strong></div>
                    <div style="float : right">
                        <div>Nombres de membres : <?= count($members); ?></div> 
                    </div>
                </th>
            </tr>
        </thead>
        <tbody>
            <tr class="text-center">
                <th>NOM</th>
                <th>Prénom</th>
                <th>Adresse email</th>
                <th>Options</th>
            </tr>

            <?php
            foreach($members as $member)
            {
            ?>
            <tr class="text-center">
                <td>
                    <div id="nom<?= $member->rowid ?>" class="collapse show">
                        <?= $member->lastname; ?>
                    </div>

                    <div id="divModifNom<?= $member->rowid ?>" class="collapse show mt-3">
                        <a onclick="afficherConfModifNom(<?= $member->rowid ?>)" class="btn btn-outline-info">Modifier</a>
                    </div>
                    <form method="POST" action="<?= CONTROLLERS_URL ?>admin/listeMembres.php?action=updateLastname&idUser=<?= $member->rowid ?>">
                        <div id="divInputModifNom<?= $member->rowid ?>" class="collapse">
                            <input class="form-control mb-1 text-center w-50 mx-auto" value="<?= $member->lastname ?>" type="text" name="lastname" placeholder="Écrivez un nom" required>
                        </div>

                        <!-- Confirmer la modification du nom de l'utilisateur -->
                        <div id="divConfModifNom<?= $member->rowid ?>" class="collapse">
                            <button type="submit" name="envoi" value="<?= true ?>" class="btn btn-success">Confirmer</button>
                            <a onclick="annulerModifNom(<?= $member->rowid ?>)" class="btn btn-warning">Annuler</a>
                        </div>
                    </form>
                </td>
                
                <td>
                    <div id="prenom<?= $member->rowid ?>" class="collapse show">
                        <?=$member->firstname;?>
                    </div>

                    <div id="divModifPrenom<?= $member->rowid ?>" class="collapse show mt-3">
                        <a onclick="afficherConfModifPrenom(<?= $member->rowid ?>)" class="btn btn-outline-info">Modifier</a>
                    </div>
                    <form method="POST" action="<?= CONTROLLERS_URL ?>admin/listeMembres.php?action=updateFirstname&idUser=<?= $member->rowid ?>">
                        <div id="divInputModifPrenom<?= $member->rowid ?>" class="collapse">
                            <input class="form-control mb-1 text-center w-50 mx-auto" value="<?= $member->firstname ?>" type="text" name="firstname" placeholder="Écrivez un prénom" required>
                        </div>

                        <!-- Confirmer la modification du nom de l'utilisateur -->
                        <div id="divConfModifPrenom<?= $member->rowid ?>" class="collapse">
                            <button type="submit" name="envoi" value="<?= true ?>" class="btn btn-success">Confirmer</button>
                            <a onclick="annulerModifPrenom(<?= $member->rowid ?>)" class="btn btn-warning">Annuler</a>
                        </div>
                    </form>
                </td>

                <td class="align-middle"><?= $member->email;?></td>

                <td class="align-middle">
                    <div id="divDelUser<?= $member->rowid ?>" class="collapse show">
                        <button onclick="afficherConfDelUser(<?= $member->rowid ?>)" class="btn btn-outline-danger">Supprimer</button>
                    </div>
                    <div id="divConfDelUser<?= $member->rowid ?>" class="collapse">
                        <div>
                            <div class="col-6">
                                <a href="<?= CONTROLLERS_URL ?>admin/listeMembres.php?action=deleteUser&idUser=<?= $member->rowid ?>" class="btn btn-outline-danger">Confirmer</a><br>
                            </div>
                            <div class="col-6 mt-2">
                                <button onclick="annulerDelUser(<?= $member->rowid ?>)" class="btn btn-outline-warning">Annuler</button>
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