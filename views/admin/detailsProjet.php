<?php
require_once 'layouts/entete.php';
?>

<div class="col-10">

<?php
if($errors)
{ ?>
    <div class="alert alert-danger">

        <?php
    foreach($errors as $error)
    {
        echo $error . "<br>";
    } ?>
    </div>
    <?php 
}
else if ($success)
{ ?>
    <div class="alert alert-success">
    <?php
    echo $success;
    ?>
    </div>
    <?php
}


?>
    <div class="row">

        <div class="sticker col-3 mt-3 ms-3 me-3 text-center overflow-x" style="height: 60px; ">
            <h3 class="mt-2"><?= $CurrentProject->name; ?></h3>
        </div>
        
        <div class="sticker col mt-3 me-4 text-center">
            <h3 class="text-center mt-2">Equipes</h3>
        </div>

    </div>

    <div class="row">
        <div class="sticker col-3 mt-3 ms-3 me-3 text-center" style="height: 75vh;position:relative;margin-top:5%px !important;">
            <form action="" method="POST">
                <h5 class="mt-5 border-bottom w-50 mx-auto">Titre</h5>
                <input class="sticker text-center mt-2" name="name" id="name" type="text" value="<?= $CurrentProject->name ?>">

                <h5 class="mt-3 border-bottom w-50 mx-auto">Description</h5>
                <textarea class="sticker text-center mt-2" name="description" id="description" type="text"><?= $CurrentProject->description ?></textarea>

                <h5 class="mt-3 border-bottom w-50 mx-auto">Type</h5>
                <input class="sticker text-center mt-2" name="type" id="type" type="text" value="<?= $CurrentProject->type ?>">

                <div class="row">
                    <button class="btn btn-outline-primary mt-4 w-75 mx-auto" style="bottom:4%; left:0; right:0 ; margin-left:auto; margin-right:auto; position:absolute;" type="submit">Update</button>
                </div>

            </form>
        </div>

        <div class="sticker col mt-3 me-4 text-center" style="height: 75vh;">
            
            <div class="row">
                <div class="col-4 mt-3" style="position:relative;margin-top:5%px !important;">
                    <button id="team-switch-button" class="btn btn-secondary w-100">Basculer vers equipes</button>

                    <h5 class="mt-3">Nom équipe</h5>
                    <input class="sticker mt-1 w-100 text-center" type="text">

                    <!-- AFFICHER TOUTES LES EQUIPES EXISTANTES POUR CE PROJET -->

                    <button class="btn btn-outline-primary w-75 text-center" type="submit" style="bottom:0; left:0; right:0 ; margin-left:auto; margin-right:auto; position:absolute;">Update</button>
                </div>
                
                <div class="col-4 mt-3">
                    <!-- DIV DE SELECTION DES EQUIPES AJOUTEES AU PROJET ET QUE L ON PEUT RETIRER -->
                    <div class="card collapse w-100">
                        <div class="card-header text-center">
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
                                    foreach($equipes as $key => $equipe)
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
                        </div>
                    </div>

                    <div class="card w-100" style="height: 70vh;">
                        <div class="card-header text-center">
                            <h3>Membres affectés</h3>
                        </div>
                        <div class="card-body">
                            <table class="table">
                                <tbody class="tbodyEquipeProjet" style="width: max-content;">
                                    <tr>
                                        <th>Nom</th>
                                        <th>Prénom</th>
                                        <th>Options</th>
                                    </tr>
                                </tbody>
                                <?php 
                                // ajouter les membres ajoutés avec js
                                ?>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-4 mt-3">
                    <!-- DIV DE SELECTION DES EQUIPES A AJOUTER AU PROJET -->
                        <div class="card collapse w-100">
                            <div class="card-header text-center">
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
                                        foreach($equipes as $key => $equipe)
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
                            </div>
                        </div>

                        <div class="card w-100" style="height: 70vh;">
                            <div class="card-header text-center">
                                <h3>Membres prêts</h3>
                            </div>
                            <div class="card-body">
                                <table class="table">
                                    <tbody class="text-center">
                                        <tr>
                                            <th>Nom</th>
                                            <th>Prénom</th>
                                            <th>Options</th>
                                        </tr>
                                        <?php
                                        foreach($projectFreeUsers as $key => $user)
                                        {
                                            ?>
                                            <tr class="collapse show" id="user<?= $user->idUtilisateur ?>">
                                                <td><?= $user->nom ?></td>
                                                <td><?= $user->prenom ?></td>
                                                <td><button onclick="addUserToTeam(<?= $user->idUtilisateur ?>)" class="btn btn-outline-success">Ajouter</button></td>
                                            </tr>
                                            <?php
                                        } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                </div>
            </div>

        </div>


<?php 

require_once 'layouts/pied.php';

?>