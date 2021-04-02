<?php
require_once "entete.php";
?>
<div class="col-10">
<?php
$roles = recupererRoles();
$postes = recupererPostes($_SESSION["idOrganisation"]);
$equipes = recupererEquipes($_SESSION["idOrganisation"]);
$nbMembresEquipes = recupererNombreMembreParEquipe($_SESSION["idOrganisation"]);
$nbMembresPostes = recupererNombreMembreParPoste($_SESSION["idOrganisation"]);
$equipeMinMax = recupererMaxMinIdEquipes($_SESSION["idOrganisation"]);

if(!empty($_GET["id"]))
{
    $posteModif = recupIdPoste($_GET["id"]);
}
if(!empty($_GET["error"]))
{
    ?>
    <div class="alert alert-danger pb-0 mt-3" style="z-index : 0">
    <?php
    switch($_GET["error"]) { case "fatalerror":?>
        <?php echo "Erreur : une erreur inconnu est survenue."?>
        <?php break ?>
    <?php
    }
    ?>
    </div>
<?php
}
if(!empty($_GET["success"]))
{
    ?>
    <div class="alert alert-success mt-3" style="z-index : 0">
    <?php
    switch($_GET["success"]) { case "ajouterPoste":?>
        <?php echo "Le poste a bien été ajouté."?>
        <?php break;?>
            <?php case "modifierPoste";?>
        <?php echo "Le poste a bien été modifié"?> 
        <?php break;?>
            <?php case "supprimerPoste";?>
        <?php echo "Le poste a bien été supprimé"?>
        <?php break;?>
            <?php case "ajouterEquipe";?>
        <?php echo "L'équipe a bien été ajouté"?>
        <?php break;?>
    <?php
    }
    ?>
    </div>
<?php
}
if(!empty($_GET["supprPoste"]))
{
    $lePoste = recupIdPoste($_GET["supprPoste"])
    ?>
    <div class=" alert alert-info mt-3" style="z-index : 0">
    êtes vous sur de vouloir supprimer "<?=$lePoste["nomPoste"];?> ? Cette action est irréversible et supprimera le poste de tous les membres ayant ce poste ! 
    <a href="../traitements/supprimerPoste.php?suppr=<?=$_GET["supprPoste"];?>" class="btn btn-success">Confirmer</a>
    <a href="gererEntreprise.php" class="btn btn-danger">Annuler</a>
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
                    foreach($postes as $cle => $poste)
                    {
                        if($cle == "indéfini")
                        {
                            foreach($nbMembresPostes as $nbMembrePoste)
                            {
                                if($poste["idPoste"] == $nbMembrePoste["idPoste"])
                                {
                                    ?>
                                    <tr>
                                        <th style="width : 50%"><?=$poste["nomPoste"];?></th>
                                        <td style="width : 50%"><?=$nbMembrePoste["UtilisateursParPoste"];?></td>
                                        <td style="width : "></td>
                                        <td></td>
                                    </tr>
                                <?php 
                                }           
                            }
                        } else {
                            foreach($nbMembresPostes as $nbMembrePoste)
                            {
                                if($poste["idPoste"] == $nbMembrePoste["idPoste"])
                                {
                                    ?>
                                    <tr style="width : 150%">
                                        <th><?=$poste["nomPoste"];?></th>
                                        <td><?=$nbMembrePoste["UtilisateursParPoste"];?></td>
                                        <td>
                                            <a href="gererEntreprise.php?supprPoste=<?=$poste["idPoste"];?>" class="btn btn-danger btn-sm mt-1">Supprimer</a>
                                        </td>
                                        <td>
                                            <a href="gererEntreprise.php?id=<?=$poste["idPoste"];?>" class="btn btn-primary btn-sm mt-1">Modifier</a>
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
            <form method="post" action="../traitements/ajouterPoste.php"> 
                <div class="row">
                    <div class="col-6">
                       <div class="form-group text-center">
                            <label for="ajoutPoste" class="mb-2">Nom du poste</label>
                            <input type="text" class="form-control" name="ajoutPoste" id="ajoutPoste" placeholder="Nouveau poste" required>
                        </div> 
                    </div>
                    <div class="col-6">
                        <div class="form-group text-center">
                            <label for="role" class="mb-2">Habilitation</label>
                            <select name="role" id="role" class="form-control">
                                <?php
                                foreach($roles as $cle => $role)
                                {
                                    ?>
                                    <option value="<?=$role["idRole"];?>"  <?=$role["nom"] == "Collaborateur" ? "selected" : "" ;?>  ><?=$role["nom"];?></option>
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
            if(!empty($_GET["id"]))
            {
            ?>
                <form method="post" action="../traitements/modifierPoste.php?id=<?=$_GET["id"];?>">
                    <div class="form-group ml-auto mr-auto mt-3">
                        <label for="modifierPoste">Modifier le poste "<?=$posteModif["nomPoste"];?>"</label>
                        <input type="text" class="form-control" name="modifierPoste" id="modifierPoste" placeholder="entrez le nouveau nom du poste" value="<?=$posteModif["nomPoste"];?>">
                    </div>

                    <div class="form-group text-center mt-2">
                        <button type="submit" class="btn btn-info" name="envoi" value="1" >Modifier le poste</button>
                        <a href="gererEntreprise.php" class="btn btn-warning mt-1">Annuler</a>
                    </div>
                </form>
            <?php
            }
            ?>
        </div>
    </div>

    <div id="modifEquipe">
        <div class="infoEquipe">
            <form method="post" action="../traitements/ajoutEquipe.php"> 
                <div class="form-group mt-3">
                    <label for="ajoutEquipe"><h4>Nom de la nouvelle équipe</h4></label>
                </div>
                <div class="form-group mt-3">
                    <input type="text" class="form-control" name="ajoutEquipe" id="ajoutEquipe" placeholder="Saisissez le nom de l'équipe" required>
                </div>
                
                <div class="form-group text-center m-auto mt-3">
                    <button type="submit" class="btn btn-success" name="envoi" value="1" >Ajouter l'équipe</button>
                </div>
            </form>
        </div>

        <div class="infoEquipe">
            <h4><u>Info de l'équipe :</u></h4>
            <?php
            foreach($equipes as $equipe)
            {
                $membresEquipe = recupUtilisateursEquipe($equipe["idEquipe"]);
                $projets = recupProjetParEquipe($equipe["idEquipe"]);
                ?>
                <div class="mt-5" id="divInfoEquipe<?=$equipe["idEquipe"];?>" style="display : none">
                    <h3><strong><?=$equipe["nomEquipe"];?></strong></h3>
                    <h4>Chef d'équipe :</h4>
                    <?php
                    if($equipe["chefEquipe"] == NULL){
                        ?>
                        <p>//</p>
                    <?php
                    } else {
                        ?>
                        <p><?=$equipe["chefEquipe"];?></p>
                    <?php
                    }
                    ?>
    
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
                            foreach($membresEquipe as $membreEquipe)
                            {
                                $posteUtilisateur = recupererPosteUtilisateur($membreEquipe["idUtilisateur"]);
                                ?>
                                <tr>
                                    <td><?=$membreEquipe["nom"];?></td>
                                    <td><?=$membreEquipe["prenom"];?></td>
                                    <td><?=$posteUtilisateur["nomPoste"];?></td>
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
                            foreach($projets as $projet)
                            {
                                ?>
                            <tr>
                                <td>
                                    <div style="float : left"><?=$projet["nom"];?></div>
                                    <div style="float : right">"Barre de complétion"</div>
    
                                </td>
                            </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                    <div class="text-center">
                        <a href="infoEquipe.php?idEquipe=<?=$equipe["idEquipe"];?>" class="btn btn-secondary">allez a la page de modification de l'équipe</a>
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
                        <th><strong>Nom de l'équipe</strong></th>
                        <th><strong>Nb membre</strong></th>
                        <th><strong>Nom du chef d'équipe</strong></th>
                        <th><strong>info</strong></th>   
                    </tr>
                </thead>
            </table>
            <table class="table m-0">
                <tbody id="tbodyEquipe">
                <?php
                foreach($equipes as $cle => $equipe)
                {
                    if($cle == "indéfini")
                    {
                        foreach($nbMembresEquipes as $nbMembreEquipe)
                        {
                            if($equipe["idEquipe"] == $nbMembreEquipe["idEquipe"])
                            {
                                ?>
                                    <tr>
                                        <th style="width: 33%"><?=$equipe["nomEquipe"];?></th>
                                        <td style="width: 33%"><?=$nbMembreEquipe["UtilisateursParEquipe"];?></td>
                                        <?php
                                        if($equipe["chefEquipe"] == NULL){
                                        ?>
                                            <td style="width: 33%">//</td>
                                        <?php
                                        } else {
                                        ?>
                                            <td style="width: 33%"><?=$equipe["chefEquipe"];?></td>
                                        <?php
                                        }
                                        ?>
                                        <td></td>
                                    </tr>

                                <?php
                            }
                        }
                    } else {
                        foreach($nbMembresEquipes as $nbMembreEquipe)
                        {
                            if($equipe["idEquipe"] == $nbMembreEquipe["idEquipe"])
                            {
                                ?>
                                    <tr>
                                        <th><?=$equipe["nomEquipe"];?></th>
                                        <td><?=$nbMembreEquipe["UtilisateursParEquipe"];?></td>
                                        <?php
                                        if($equipe["chefEquipe"] == NULL){
                                        ?>
                                            <td>//</td>
                                        <?php
                                        } else {
                                        ?>
                                            <td><?=$equipe["chefEquipe"];?></td>
                                        <?php
                                        }
                                        ?>
                                        <td><a onclick="afficherInfoEquipe(<?=$equipe['idEquipe'];?>, <?=$equipeMinMax['MinId'];?>, <?=$equipeMinMax['MaxId'];?>)" class="btn btn-info">Info</a></td>
                                    </tr>

                                <?php
                            }
                        }
                    }
                }
                ?>
                </tbody>
            </table>
        <?php

        ?>
    </div>
<?php
require_once "pied.php";
?>