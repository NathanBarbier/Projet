<?php
require_once "layouts/entete.php";
?>

<div class="col-10">
<?php
if($success)
{
    ?>
    <div class="alert alert-success mt-2 me-3">
    <?php
        echo $success;
    ?>
    </div>
    <?php
}
else if($errors)
{
    ?>
    <div class="alert alert-danger mt-2 me-3">
    <?php
    foreach($errors as $error)
    {
        echo $error . "<br>";
    }
    ?>
    </div>
    <?php
}
?>

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
            <th>Poste</th>
            <th>Adresse email</th>
            <th>Infos</th>
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
                        <input class="form-control mb-1 text-center" value="<?= $member->lastname ?>" type="text" name="lastname" placeholder="Écrivez un nom" required>
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
                        <input class="form-control mb-1 text-center" value="<?= $member->firstname ?>" type="text" name="firstname" placeholder="Écrivez un prénom" required>
                    </div>

                    <!-- Confirmer la modification du nom de l'utilisateur -->
                    <div id="divConfModifPrenom<?= $member->rowid ?>" class="collapse">
                        <button type="submit" name="envoi" value="<?= true ?>" class="btn btn-success">Confirmer</button>
                        <a onclick="annulerModifPrenom(<?= $member->rowid ?>)" class="btn btn-warning">Annuler</a>
                    </div>
                </form>
            </td>

            <td>
                <div id="divNomPoste<?= $member->rowid ?>" class="collapse show">
                    <?php 
                    foreach($positions as $position)
                    {
                        // var_dump($poste->idPoste);
                        // var_dump($member);
                        if($position->rowid == $member->fk_position)
                        {
                            echo $position->name;
                            break;
                        }

                    }
                    ?>
                </div>
                <!-- On affiche tous les postes de l'organisation -->
                <form method="POST" action="<?= CONTROLLERS_URL ?>admin/listeMembres.php?action=updatePosition&idUser=<?= $member->rowid ?>">
                    <div id="divSelectPostes<?= $member->rowid ?>" class="collapse text-center">
                        <select name="idPosition" class="text-center form-control w-75 mx-auto mb-1">
                            <?php foreach($positions as $position)
                            { 
                                ?>
                                <option value="<?= $position->rowid ?>" <?= $position->rowid == $member->fk_position ? "selected" : "" ?>><?= $position->name ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div id="divModifPoste<?= $member->rowid ?>" class="collapse show mt-3">
                        <a onclick="afficherConfModifPoste(<?= $member->rowid ?>)" class="btn btn-outline-info">Modifier</a>
                    </div>
                    <div id="divConfModifPoste<?= $member->rowid ?>" class="collapse">
                        <button type="submit" class="btn btn-outline-success">Confirmer</button><br>

                        <a onclick="annulerModifPoste(<?= $member->rowid ?>)" class="btn btn-outline-danger mt-1">Annuler</a>
                    </div>
                </form>
            </td>

            <td class="align-middle"><?= $member->email;?></td>

            <td class="align-middle">
                <a href="<?= CONTROLLERS_URL ?>admin/infoMembre.php?id=<?=$member->rowid;?>" class="btn btn-outline-primary">Fiches </a>
            </td>
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