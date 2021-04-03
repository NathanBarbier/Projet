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
                <div class="form-floating mb-3">
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
                        <a href="#" class="btn btn-outline-success" style="height: 75%;"><img src="../images/check.png" width="40px"></a>
                    </div>
                </div>

                <button class="btn btn-outline-primary mt-3">Créer le projet</button>
            </form>

            </form>
        </div>

        <div class="col-6">
            <!-- SELECT CHEF DE PROJET PARMIS CHEF D EQUIPES AJOUTEES AU PROJET-->
            <div class="row">
                <div class="col">
                    <div class="card" style="height: 20vh;">
                        <div class="card-header">
                            <h3>Chef de projet</h3>
                        </div>
                        <div class="card-body">
                            <select class="form-control">
                                <option selected>Choisir un chef de projet</option>
                                <?php 
                                foreach($equipes as $equipe)
                                {
                                    $chefEquipe = recupChefEquipe(intval($equipe['chefEquipe']));
                                    ?>
                                    <option class="collapse" id="selectChefEquipe<?= $equipe['idEquipe'] ?>"><?= $chefEquipe[0]['prenom'] . " " . $chefEquipe[0]['nom'] ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- DIV DE SELECTION DES EQUIPES AJOUTEES AU PROJET ET QUE L ON PEUT RETIRER -->
            <div class="row mt-2">
                <div class="col-6">
                    <div class="card" style="width: max-content;">
                        <div class="card-header text-center">
                            <h3>Équipes ajoutées</h3>
                        </div>
                        <div class="card-body">
                            <table class="table" style="width: max-content;">
                                <tbody class="tbodyEquipeProjet" style="width: max-content;">
                                    <tr>
                                        <th>Nom</th>
                                        <th>Chef</th>
                                        <th>Options</th>
                                    </tr>
                                    <?php 
                                    foreach($equipes as $equipe)
                                    {
                                        $chefEquipe = recupChefEquipe(intval($equipe['chefEquipe']));
                                        ?>
                                        <tr class="collapse" id="equipeProjet<?= $equipe['idEquipe'] ?>">
                                            <td><?= $equipe["nomEquipe"] ?></td>
                                            <td><?= $chefEquipe[0]['prenom'] . " " . $chefEquipe[0]['nom'] ?></td>
                                            <td><button onclick="retirerEquipe(<?= $equipe['idEquipe'] ?>)" class="btn btn-outline-danger">Retirer</button></td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- DIV DE SELECTION DES EQUIPES A AJOUTER AU PROJET -->
                <div class="col-6 d-flex justify-content-end">
                    <div class="card" style="width: max-content;">
                        <div class="card-header text-center">
                            <h3>Équipes prêtes</h3>
                        </div>
                        <div class="card-body">
                            <table class="table">
                                <tbody class="tbodyEquipeProjet">
                                    <tr>
                                        <th>Nom</th>
                                        <th>Chef</th>
                                        <th>Options</th>
                                    </tr>
                                    <?php 
                                    foreach($equipes as $equipe)
                                    {
                                        $chefEquipe = recupChefEquipe(intval($equipe['chefEquipe']));
                                        ?>
                                        <tr class="collapse show" id="equipe<?= $equipe['idEquipe'] ?>">
                                        <td><?= $equipe["nomEquipe"] ?></td>
                                        <td><?= $chefEquipe[0]['prenom'] . " " . $chefEquipe[0]['nom'] ?></td>
                                            <td><button onclick="ajouterEquipe(<?= $equipe['idEquipe'] ?>)" class="btn btn-outline-success">Ajouter</button></td>
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
        </div>
    </div>
</div>

<script>
    function ajouterEquipe(idEquipe)
    {
        var identifiantEquipe = "equipe" + idEquipe;
        var identifiantEquipeProjet = "equipeProjet" + idEquipe;
        var identifiantSelectChefEquipe = "selectChefEquipe" + idEquipe;
        document.getElementById(identifiantEquipe).classList.remove("show");
        document.getElementById(identifiantEquipeProjet).classList.add("show");
        document.getElementById(identifiantSelectChefEquipe).classList.add("show");
    }

    function retirerEquipe(idEquipe)
    {
        var identifiantEquipe = "equipe" + idEquipe;
        var identifiantEquipeProjet = "equipeProjet" + idEquipe;
        var identifiantSelectChefEquipe = "selectChefEquipe" + idEquipe;
        document.getElementById(identifiantEquipe).classList.add("show");
        document.getElementById(identifiantEquipeProjet).classList.remove("show");
        document.getElementById(identifiantSelectChefEquipe).classList.remove("show");

    }
</script>

<?php require_once "pied.php"; ?>