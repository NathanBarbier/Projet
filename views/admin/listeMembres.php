<?php
require_once "layouts/entete.php";

if($success)
{
    ?>
    <div class="alert alert-success">
    <?php
        echo $success;
    ?>
    </div>
    <?php
}
else if($error)
{
    ?>
    <div class="alert alert-danger">
    <?php
        echo $error;
    ?>
    </div>
    <?php
}
?>

<div class="col-10">

<table class="table">
    <thead>
        <tr>
            <th colspan="7">
                <div style="float : left"><strong>Liste des membres</strong></div>
                <div style="float : right">
                    <div>Nombres de membres : <?=count($membres);?></div> 
                </div>
            </th>
        </tr>
    </thead>
    <tbody>
        <tr class="text-center">
            <th>NOM</th>
            <th>Prénom</th>
            <th>Équipe</th>
            <th>Poste</th>
            <th>Adresse email</th>
            <th>Infos</th>
            <th>Options</th>
        </tr>

        <?php
        foreach($membres as $membre)
        {
        ?>
        <tr class="text-center">
            <td>
                <div id="nom<?= $membre['idUtilisateur'] ?>" class="collapse show">
                    <?=$membre["nom"];?>
                </div>

                <div id="divModifNom<?= $membre['idUtilisateur'] ?>" class="collapse show">
                    <a onclick="afficherConfModifNom(<?= $membre['idUtilisateur'] ?>)" class="btn btn-outline-info">Modifier</a>
                </div>
                <form method="POST" action="listeMembres.php?action=updateLastname&idUser=<?= $membre['idUtilisateur'] ?>">
                    <div id="divInputModifNom<?= $membre['idUtilisateur'] ?>" class="collapse">
                        <input class="form-control mb-1 text-center" value="<?= $membre['nom'] ?>" type="text" name="nom" placeholder="Écrivez un nom" required>
                    </div>

                    <!-- Confirmer la modification du nom de l'utilisateur -->
                    <div id="divConfModifNom<?= $membre['idUtilisateur'] ?>" class="collapse">
                        <button type="submit" name="envoi" value="<?= true ?>" class="btn btn-success">Confirmer</button>
                        <a onclick="annulerModifNom(<?= $membre['idUtilisateur'] ?>)" class="btn btn-warning">Annuler</a>
                    </div>
                </form>
            </td>
            
            <td>
                <div id="prenom<?= $membre['idUtilisateur'] ?>" class="collapse show">
                    <?=$membre["prenom"];?>
                </div>

                <div id="divModifPrenom<?= $membre['idUtilisateur'] ?>" class="collapse show">
                    <a onclick="afficherConfModifPrenom(<?= $membre['idUtilisateur'] ?>)" class="btn btn-outline-info">Modifier</a>
                </div>
                <form method="POST" action="listeMembres.php?action=updateFirstname&idUser=<?= $membre['idUtilisateur'] ?>">
                    <div id="divInputModifPrenom<?= $membre['idUtilisateur'] ?>" class="collapse">
                        <input class="form-control mb-1 text-center" value="<?= $membre['prenom'] ?>" type="text" name="prenom" placeholder="Écrivez un prénom" required>
                    </div>

                    <!-- Confirmer la modification du nom de l'utilisateur -->
                    <div id="divConfModifPrenom<?= $membre['idUtilisateur'] ?>" class="collapse">
                        <button type="submit" name="envoi" value="<?= true ?>" class="btn btn-success">Confirmer</button>
                        <a onclick="annulerModifPrenom(<?= $membre['idUtilisateur'] ?>)" class="btn btn-warning">Annuler</a>
                    </div>
                </form>
            </td>

            <td>
                <!-- On affiche l'équipe de l'utilisateur -->
                <div id="divNomEquipe<?= $membre['idUtilisateur'] ?>" class="collapse show">
                    <?=$membre["nomEquipe"];?>
                </div>
                <!-- On affiche toutes les équipe de l'organisation -->
                <form method="post" action="listeMembres.php?action=updateEquipe&idUser=<?= $membre["idUtilisateur"] ?>">
                    <div id="divSelectEquipes<?= $membre['idUtilisateur'] ?>" class="collapse text-center">
                        <select name="idEquipe" class="text-center form-control w-75 mx-auto mb-1" required>
                            <?php foreach($equipes as $equipe)
                            { 
                            ?>
                                <option value="<?= $equipe['idEquipe'] ?>"><?= $equipe["nomEquipe"] ?></option>
                            <?php 
                            } 
                            ?>
                        </select>
                    </div>

                    <!-- On affiche le bouton modifier l'équipe -->
                    <div id="divModifEquipe<?= $membre["idUtilisateur"] ?>" class="collapse show ml-auto">
                        <a onclick="afficherConfModifEquipe(<?= $membre['idUtilisateur'] ?>)" class="btn btn-outline-info">Modifier</a>
                    </div>
                    <!-- On affiche la div de confirmation de modification d'équipe -->
                    <div id="divConfModifEquipe<?= $membre["idUtilisateur"] ?>" class="collapse">
                        <button type="submit" class="btn btn-outline-success">Confirmer</button><br>

                        <a onclick="annulerModifEquipe(<?= $membre['idUtilisateur'] ?>)" class="btn btn-outline-danger mt-1">Annuler</a>
                    </div>
                </form>
            </td>

            <td>
                <div id="divNomPoste<?= $membre['idUtilisateur'] ?>" class="collapse show">
                    <?=$membre["nomPoste"];?>
                </div>
                <!-- On affiche tous les postes de l'organisation -->
                <form method="POST" action="listeMembres.php?action=updatePoste&idUser=<?= $membre['idUtilisateur'] ?>">
                    <div id="divSelectPostes<?= $membre['idUtilisateur'] ?>" class="collapse text-center">
                        <select name="idPoste" class="text-center form-control w-75 mx-auto mb-1">
                            <?php foreach($postes as $poste)
                            { ?>
                                <option value="<?= $poste['idPoste'] ?>"><?= $poste["nomPoste"] ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div id="divModifPoste<?= $membre["idUtilisateur"] ?>" class="collapse show">
                        <a onclick="afficherConfModifPoste(<?= $membre['idUtilisateur'] ?>)" class="btn btn-outline-info">Modifier</a>
                    </div>
                    <div id="divConfModifPoste<?= $membre["idUtilisateur"] ?>" class="collapse">
                        <button type="submit" class="btn btn-outline-success">Confirmer</button><br>

                        <a onclick="annulerModifPoste(<?= $membre['idUtilisateur'] ?>)" class="btn btn-outline-danger mt-1">Annuler</a>
                    </div>
                </form>
            </td>

            <td><?=$membre["email"];?></td>

            <td>
                <a href="infoMembre.php?id=<?=$membre["idUtilisateur"];?>" class="btn btn-outline-primary">Fiches </a>
            </td>
            <td>
                <div id="divDelUser<?= $membre["idUtilisateur"] ?>" class="collapse show">
                    <button onclick="afficherConfDelUser(<?= $membre['idUtilisateur'] ?>)" class="btn btn-outline-danger">Supprimer</button>
                </div>
                <div id="divConfDelUser<?= $membre["idUtilisateur"] ?>" class="collapse">
                    <div>
                        <div class="col-6">
                            <a href="../traitements/supprimerUtilisateur/php?idUser=<?= $membre["idUtilisateur"] ?>" class="btn btn-outline-danger">Confirmer</a><br>
                        </div>
                        <div class="col-6 mt-2">
                            <button onclick="annulerDelUser(<?= $membre['idUtilisateur'] ?>)" class="btn btn-outline-warning">Annuler</button>
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

<script src="<?= JS_URL ?>admin/listeMembres.php"></script>


<?php
require_once "layouts/pied.php";
?>