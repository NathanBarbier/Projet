<?php require_once "entete.php"; 
$equipes = recupererEquipes($_SESSION['idOrganisation']);
if(!empty($_GET['idProjet']))
{
    extract($_GET);
} else {
    $idProjet = recupMaxIdProjets();
    $idProjet = $idProjet['maxId'] + 1;
}
?>

<div class="col-10 mt-4">
    <h2>Création de projet</h2>
    <div class="row">
        <div class="col-4">
            <form method="POST" action="#">
                <div class="form-floating mt-3 mb-3">
                    <input class="form-control" type="text" name="titre" id="titre" placeholder="Titre du projet" value="<?= $titre ?? '' ?>">
                    <label for="titre">Titre du projet</label>
                </div>

                <div class="form-floating mb-3">
                    <input class="form-control" type="text" name="type" id="type" placeholder="Type du projet" value="<?= $type ?? '' ?>"  required>
                    <label for="type">Type du projet</label>
                </div>

                <div class="form-floating mb-3">
                    <input class="form-control" type="date" name="deadline" id="deadline" placeholder="Deadline du projet" value="<?= $deadline?? '' ?>"  required>
                    <label for="deadline">DeadLine</label>
                </div>

                <div class="form-floating mb-3">
                    <textarea class="form-control" name="description" id="description" placeholder="Description"><?= $descritpion ?? '' ?></textarea>
                    <label for="description">Description</label>
                </div>

                <div class="row">
                    <div class="col-10">
                        <div class="form-floating mb-3">
                            <input class="form-control" type="text" name="client" id="client" placeholder="Client du projet" value="<?= $client ?? '' ?>"  required>
                            <label for="client">Client</label>
                        </div>
                    </div>

                    <div class="col-2">
                        <a style="float: right" href="#" class="btn btn-outline-info">Vérifier CLient</a>
                    </div>
                </div>

                <button class="btn btn-outline-primary">Créer le projet</button>
            </form>

            </form>
        </div>

        <div class="col-4 ">
            <!-- SELECT CHEF DE PROJET PARMIS CHEF D EQUIPES AJOUTEES AU PROJET-->
            <div class="row" style="height: 35%;">
                <select>
                    <?php
                        foreach($equipesProjet as $equipeProjet)
                        {
                            ?>
                            <option><?= $equipeProjet["chefEquipe"] ?></option>
                            <?php
                        }
                    ?>
                </select>
            </div>

            <!-- DIV DE SELECTION DES EQUIPES AJOUTEES AU PROJET ET QUE L ON PEUT RETIRER -->
            <div class="row">
                <h3>Équipes ajoutées au projet</h3>
                <table class="table">
                    <tbody class="tbodyEquipeProjet">
                        <tr>
                            <th>Nom</th>
                            <th>Chef</th>
                            <th>nombre membres</th>
                            <th>Options</th>
                        </tr>
                        <?php 
                        foreach($equipes as $equipe)
                        {
                            ?>
                            <tr>
                                <td><?= $equipe["nomEquipe"] ?></td>
                                <td><?= $equipe["chefEquipe"] ?></td>
                                <td><button class="btn btn-ouline-danger">Retirer</button></td>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="col-3">
            <!-- DIV DE SELECTION DES EQUIPES A AJOUTER AU PROJET -->
            <div class="card">
                <h3>Équipes de l'organisation</h3>
                <table class="table">
                <tbody class="tbodyEquipeProjet">
                        <tr>
                            <th>Nom</th>
                            <th>Chef</th>
                            <th>nombre membres</th>
                            <th>Options</th>
                        </tr>
                        <?php 
                        foreach($equipes as $equipe)
                        {
                            ?>
                            <tr>
                                <td><?= $equipe["nomEquipe"] ?></td>
                                <td><?= $equipe["chefEquipe"] ?></td>
                                <td><a href="../traitements/travaille_sur.php?idProjet=<?= $idProjet ?>&idEquipe=<?= $equipe['idEquipe'] ?>" class="btn btn-outline-success">Ajouter</a></td>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require_once "pied.php"; ?>