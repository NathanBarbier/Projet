<?php
require_once "layouts/entete.php"; 

$data = json_decode(GETPOST('data'));
?>
<div class="col-10 mt-4">
<?php
if($data->success)
{
    ?>
    <div class="alert alert-success">
        <?= $data->success ?>
    </div>
    <?php
}
else if ($data->erreurs)
{ ?>
    <div class="alert alert-danger">
    <?php
    foreach($data->erreurs as $erreur)
    {
        echo $erreur . "<br>";
    }
    ?>
    </div>
    <?php
}
?>
    <h2>Création de projet</h2>
    <div class="row mt-4">
        <div class="col-4">
            <form method="POST" action="<?= CONTROLLERS_URL ?>admin/creationProjets.php?action=addProjet&idProjet=<?= $data->idProjet ?? '' ?>">
                <div class="form-floating mb-3">
                    <input required class="form-control" type="text" name="titre" id="titre-id" placeholder="Titre du projet" value="<?= $data->titre ?? '' ?>">
                    <label for="titre">Titre du projet</label>
                </div>

                <div class="form-floating mb-3">
                    <input required class="form-control" type="text" name="type" id="type-id" placeholder="Type du projet" value="<?= $data->type ?? '' ?>">
                    <label for="type">Type du projet</label>
                </div>

                <div class="form-floating mb-3">
                    <input class="form-control" type="date" name="deadline" id="deadline-id" placeholder="Deadline du projet" value="<?= $data->deadline ?? '' ?>">
                    <label for="deadline">DeadLine</label>
                </div>

                <div class="form-floating mb-3">
                    <textarea required class="form-control" name="description" id="description-id" placeholder="Description" maxlength="255"><?= $data->description ?? '' ?></textarea>
                    <label for="description">Description</label>
                </div>

                <div class="row">
                    <div class="col-10">
                        <div class="form-floating mb-3">
                            <input class="form-control" type="text" name="" id="undefined" placeholder="undefined" value="">
                            <label for="undefined">undefined</label>
                        </div>
                    </div>

                    <!-- <input id="inputEquipesAjoutees" class="collapse" name="equipesAjoutees" value="" required> -->
                </div>

                <button type="submit" value="<?= true ?>" name="envoi" class="btn btn-outline-primary mt-3">Créer le projet</button>

            </form>
        </div>

        <div class="col-6">
            <div class="row">
                <div class="col">
                    <div class="card" style="height: 20vh;">
                        <div class="card-header">
                            <h3>undefined</h3>
                        </div>
                        <div class="card-body">
                            <select class="form-control" name="" required>
                                <option selected>undefined</option>
                                <?php 
                                foreach($data->equipes as $key => $equipe)
                                {
                                    ?>
                                    <option class="collapse" id="">undefined</option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
            </form>
            </div>

            <!-- DIV DE SELECTION DES EQUIPES AJOUTEES AU PROJET ET QUE L ON PEUT RETIRER -->
            <div class="row mt-2">
                <div class="col-6">
                    <div class="card" style="width: max-content;">
                        <!-- <div class="card-header text-center">
                            <h3>Équipes ajoutées</h3>
                        </div>
                        <div class="card-body">
                            <table class="table" style="width: max-content;">
                                <tbody class="tbodyEquipeProjet" style="width: max-content;">
                                    <tr>
                                        <th>Nom</th>
                                        <th>Options</th>
                                    </tr>
                                    <?php 
                                    foreach($data->equipes as $key => $equipe)
                                    {
                                        ?>
                                        <tr class="collapse" id="equipeProjet<?= $equipe->idEquipe ?>">
                                            <td><?= $equipe->nomEquipe ?></td>
                                            <td><button onclick="retirerEquipe(<?= $equipe->idEquipe ?>)" class="btn btn-outline-danger">Retirer</button></td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div> -->
                    </div>
                </div>
                <!-- DIV DE SELECTION DES EQUIPES A AJOUTER AU PROJET -->
                <div class="col-6 d-flex justify-content-end">
                    <div class="card" style="width: max-content;">
                        <!-- <div class="card-header text-center">
                            <h3>Équipes prêtes</h3>
                        </div>
                        <div class="card-body">
                            <table class="table">
                                <tbody class="tbodyEquipeProjet">
                                    <tr>
                                        <th>Nom</th>
                                        <th>Options</th>
                                    </tr>
                                    <?php 
                                    foreach($data->equipes as $key => $equipe)
                                    {
                                        ?>
                                        <tr class="collapse show" id="equipe<?= $equipe->idEquipe ?>">
                                            <td><?= $equipe->nomEquipe ?></td>
                                            <td><button onclick="ajouterEquipe(<?= $equipe->idEquipe ?>)" class="btn btn-outline-success">Ajouter</button></td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/Javascript">
</script>

<script type="text/Javascript" src="<?= JS_URL ?>admin/creationProjets.js"></script>

<?php require_once "layouts/pied.php"; ?>