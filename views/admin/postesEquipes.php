<?php
require_once "layouts/entete.php";

$data = json_decode(GETPOST('data'));

?>
<div class="col-10">
<?php
if($data->erreurs)
{   ?>
    <div class="alert alert-danger mt-3" style="z-index : 0; width : max-content">
    <?php
    foreach($data->erreurs as $erreur)
    {
        echo $erreur . "<br>";
    }
    ?>
    </div>
    <?php
}

if($data->success)
{   
?>
    <div class="alert alert-success mt-3" style="z-index : 0; width : max-content">
        <?= $data->success ?>
    </div>
<?php
}

if($data->deletePoste)
{
    ?>
    <div class=" alert alert-info mt-3 text-center" style="z-index : 0; width : max-content">
        Êtes vous sur de vouloir supprimer <?= $data->fetchPoste->nomPoste;?> ? <br>
        Cette action est irréversible et supprimera le poste de tous les membres ayant ce poste ! 
        <div class="text-center mt-3">
            <a href="<?= CONTROLLERS_URL ?>admin/postesEquipes.php?action=deletePosteConf&idPoste=<?=$data->idPoste?>" class="btn btn-success">Confirmer</a>
            <a href="<?= CONTROLLERS_URL ?>admin/postesEquipes.php" class="btn btn-danger">Annuler</a>
        </div>
    </div>
<?php
}
?>
    <div class="container w-75 mt-3" id="containerPoste">
        <div style="width : 55%; margin-right : 3%; float : left">
            <table class="table mb-0">
                <thead class="titreTable">
                    <tr>
                        <th colspan="6">
                            <div style="float : left"><strong>Liste des postes : </strong></div>
                        </th>
                    </tr>
                    <tr>
                        <th style="width : 30%"><strong>Nom du poste</strong></th>
                        <th style="width : 50%"><strong>Nombre de membre</strong></th>
                        <th><strong>Options</strong></th>  
                    </tr>
                </thead>
            </table>
            <table class="table">
                <tbody id="tbodyPoste">
                    <?php
                    foreach($data->postes as $cle => $poste)
                    {
                        if($poste->nomPoste == "indéfini")
                        {
                            foreach($data->nbMembresPostes as $key => $nbMembrePoste)
                            {
                                if($poste->idPoste == $key)
                                {
                                    ?>
                                    <tr>
                                        <th style="width : 50%"><?=$poste->nomPoste;?></th>
                                        <td style="width : 50%"><?=$nbMembrePoste;?></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                <?php 
                                }           
                            }
                        }
                    }

                    foreach($data->postes as $cle => $poste)
                    {
                        if($poste->nomPoste != "indéfini") {
                            foreach($data->nbMembresPostes as $key => $nbMembrePoste)
                            {
                                if($poste->idPoste == $key)
                                {
                                    ?>
                                    <tr style="width : 150%">
                                        <th><?=$poste->nomPoste;?></th>
                                        <td><?=$nbMembrePoste;?></td>
                                        <td>
                                            <a href="<?= CONTROLLERS_URL ?>admin/postesEquipes.php?action=deletePoste&idPoste=<?=$poste->idPoste?>" class="btn btn-danger btn-sm mt-1">Supprimer</a>
                                        </td>
                                        <td>
                                            <a href="<?= CONTROLLERS_URL ?>admin/postesEquipes.php?action=updatePoste&idPoste=<?=$poste->idPoste?>" class="btn btn-primary btn-sm mt-1">Modifier</a>
                                        </td>
                                    </tr>
                                <?php 
                                }           
                            }
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <div class="container mt-5 text-center" style="width : 30% ; float : left">
            <form method="post" action="<?= CONTROLLERS_URL ?>admin/postesEquipes.php?action=addPoste"> 
                <div class="row">
                    <div class="col-6">
                       <div class="form-group text-center">
                            <label for="nomPoste" class="mb-2">Nom du poste</label>
                            <input type="text" class="form-control" name="nomPoste" id="nomPoste-id" placeholder="Nouveau poste" required>
                        </div> 
                    </div>
                    <div class="col-6">
                        <div class="form-group text-center">
                            <label for="idRole" class="mb-2">Habilitation</label>
                            <select name="idRole" id="idRole-id" class="form-control">
                                <?php
                                foreach($data->roles as $cle => $role)
                                {
                                    ?>
                                    <option value="<?=$role->idRole;?>"  <?=$role->nom == "Collaborateur" ? "selected" : "" ;?>  ><?=$role->nom;?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>        
                
                <div class="form-group text-center m-auto mt-2">
                    <button type="submit" class="btn btn-success" name="envoi" value="1" >Ajouter le poste</button>
                </div>
            </form>
            <?php
            if($data->idPoste && $data->updatePoste)
            {
            ?>
                <form method="post" action="<?= CONTROLLERS_URL ?>admin/postesEquipes.php?action=updatePosteConf&idPoste=<?=$data->idPoste?>">
                    <div class="form-group ml-auto mr-auto mt-3">
                        <label for="modifierPoste">Modifier le poste "<?=$data->fetchPoste->nomPoste;?>"</label>
                        <input type="text" class="form-control" name="nomPoste" id="nomPoste-id" placeholder="entrez le nouveau nom du poste" value="<?=$data->fetchPoste->nomPoste;?>">
                    </div>

                    <div class="form-group text-center mt-2">
                        <button type="submit" class="btn btn-info" name="envoi" value="1" >Modifier le poste</button>
                        <a href="<?= CONTROLLERS_URL ?>admin/postesEquipes.php" class="btn btn-warning mt-1">Annuler</a>
                    </div>
                </form>
            <?php
            }
            ?>
        </div>
    </div>

    <div id="modifEquipe">
        <div class="infoEquipe">
            <form method="post" action="<?= CONTROLLERS_URL ?>admin/postesEquipes.php?action=addEquipe"> 
                <div class="form-group mt-3">
                    <label for="ajoutEquipe"><h4>Nom de la nouvelle équipe</h4></label>
                </div>
                <div class="form-group mt-3">
                    <input type="text" class="form-control" name="nomEquipe" id="nomEquipe-id" placeholder="Saisissez le nom de l'équipe" required>
                </div>
                
                <div class="form-group text-center m-auto mt-3">
                    <button type="submit" class="btn btn-success" name="envoi" value="1" >Ajouter l'équipe</button>
                </div>
            </form>
        </div>

        <div class="infoEquipe">
            <h4><u>Info de l'équipe :</u></h4>
            <?php
            foreach($data->equipes as $key => $equipe)
            {
                $membresEquipe = $data->membresEquipes[$key][0] ?? "";
                $projetsEquipe = $data->projetsEquipes[$key] ?? "";
                ?>
                <div class="mt-5" id="divInfoEquipe<?=$equipe->idEquipe;?>" style="display : none">
                    <h3><strong><?=$equipe->nomEquipe;?></strong></h3>
    
                    <table class="table">
                        <thead  class="titreTable">
                            <tr>
                                <th colspan="3"><h5>Membres de l'équipe : </h5></th>
                            </tr>
                        </thead>
                    </table>
                    <table class="table">
                        <tbody>
                            <tr>
                                <th>Nom</th>
                                <th>prenom</th>
                                <th>poste</th>
                            </tr>
                            <?php
                            foreach($membresEquipe as $membre)
                            {
                                // var_dump($membre->nom);
                                ?>
                                <tr>
                                    <td><?= $membre->nom ?? '' ;?></td>
                                    <td><?= $membre->prenom ?? '' ;?></td>
                                    <?php
                                    foreach($data->postes as $poste)
                                    {
                                        if(is_object($poste->idPoste) && is_object($membre->idPoste))
                                        {
                                            if($poste->idPoste == $membre->idPoste)
                                            {
                                                ?>
                                                <td><?= $poste->nomPoste ?? '';?></td>
                                                <?php
                                            }
                                        }
                                    }
                                    ?>
                                </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                    <table class="table">
                        <thead class="titreTable">
                            <tr>
                                <th colspan="3"><h5>Projet de l'équipe : </h5></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach($projetsEquipe as $projet)
                            {
                                ?>
                            <tr>
                                <td>
                                    <div style="float : left"><?= $projet->nom ?? '';?></div>
                                    <div style="float : right">"Barre de complétion"</div>
    
                                </td>
                            </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                    <div class="text-center">
                        <a href="<?= CONTROLLERS_URL ?>admin/infoEquipe.php?idEquipe=<?=$equipe->idEquipe;?>" class="btn btn-secondary">allez à la page de modification de l'équipe</a>
                    </div>
                    
                </div>
                <?php
            }
            ?>
        </div>
        
    </div>

    <div style="width : 60%">
        <table class="table mb-0 mt-5">
            <thead class="titreTable">
                <tr>
                    <th colspan="6">
                        <div style="float : left"><strong>Liste des equipes : </strong></div>
                    </th>
                </tr>
                <tr>
                    <th style="width: 35%;"><strong>Nom de l'équipe</strong></th>
                    <th style="width: 30%;"><strong>Nb membre</strong></th>
                    <th style="width: 35%;"><strong>Détails</strong></th>   
                </tr>
            </thead>
        </table>
        <table class="table m-0">
            <tbody id="tbodyEquipe">
            <?php
            foreach($data->equipes as $cle => $equipe)
            {
                if($equipe->nomEquipe == "indéfini")
                {
                    foreach($data->nbMembresEquipes as $key => $nbMembreEquipe)
                    {
                        if($equipe->idEquipe == $key)
                        {
                            ?>
                                <tr>
                                    <td style="width: 35%"><b><?=$equipe->nomEquipe;?></b></td>
                                    <td style="width: 30%;"><?=$nbMembreEquipe;?></td>
                                    <td style="width: 35%;"></td>
                                </tr>

                            <?php
                        }
                    }
                } 
            }

            foreach($data->equipes as $cle => $equipe)
            {
                if($equipe->nomEquipe != "indéfini")
                {
                    foreach($data->nbMembresEquipes as $key => $nbMembreEquipe)
                    {
                        if($equipe->idEquipe == $key)
                        {
                            ?>
                                <tr>
                                    <td style="width: 35%"><b><?=$equipe->nomEquipe;?></b></td>
                                    <td style="width: 30%;"><?=$nbMembreEquipe;?></td>
                                    <td style="width: 35%"><a onclick="afficherInfoEquipe(<?=$equipe->idEquipe;?>, <?= $data->equipeMinMax->minIdE ;?>, <?=$data->equipeMinMax->maxIdE;?>)" class="btn btn-info">Détails</a></td>
                                </tr>
                            <?php
                        }
                    }
                }
            }
            ?>
            </tbody>
        </table>
    </div>

    <script src="<?= JS_URL ?>admin/postesEquipes.js"></script>
<?php
require_once "layouts/pied.php";
?>