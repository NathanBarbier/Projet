<?php
require_once "entete.php";
?>
<div class="col-10">
<?php
if($error)
{   ?>
    <div class="alert alert-danger pb-0 mt-3" style="z-index : 0; width : max-content">
    <?= $errorMessage ?>
    </div>
    <?php
}

if($success)
{
    ?>
    <div class="alert alert-success mt-3" style="z-index : 0; width : max-content">
    <?= $successMessage ?>
    </div>
<?php
}


if($deletePoste)
{
    ?>
    <div class=" alert alert-info mt-3" style="z-index : 0; width : max-content">
    Êtes vous sur de vouloir supprimer "<?=$fetchPoste["nomPoste"];?> ? 
    Cette action est irréversible et supprimera le poste de tous les membres ayant ce poste ! 
    <a href="../controllers/EntrepriseController.php?action=deletePosteConf&idPoste=<?=$idPoste?>" class="btn btn-success">Confirmer</a>
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
                                        <td></td>
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
                                            <a href="gererEntreprise.php?action=deletePoste&idPoste=<?=$idPoste?>" class="btn btn-danger btn-sm mt-1">Supprimer</a>
                                        </td>
                                        <td>
                                            <a href="gererEntreprise.php?action=updatePoste&idPoste=<?=$idPoste?>" class="btn btn-primary btn-sm mt-1">Modifier</a>
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
            <form method="post" action="../controllers/EntrepriseController.php?action=addPoste"> 
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
            if($idPoste)
            {
            ?>
                <form method="post" action="../controllers/EntrepriseController.php?action=updatePoste&idPoste=<?=$idPoste?>">
                    <div class="form-group ml-auto mr-auto mt-3">
                        <label for="modifierPoste">Modifier le poste "<?=$fetchPoste["nomPoste"];?>"</label>
                        <input type="text" class="form-control" name="nomPoste" id="nomPoste-id" placeholder="entrez le nouveau nom du poste" value="<?=$fetchPoste["nomPoste"];?>">
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
            <form method="post" action="../controllers/EntrepriseController.php?action=addEquipe"> 
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
            foreach($equipes as $key => $equipe)
            {
                $membresEquipe = $membresEquipes[$key];
                $projetsEquipe = $projetsEquipes[$key];
                ?>
                <div class="mt-5" id="divInfoEquipe<?=$equipe["idEquipe"];?>" style="display : none">
                    <h3><strong><?=$equipe["nomEquipe"];?></strong></h3>
                    <h4>Chef d'équipe :</h4>
                    <?php
                    if($equipe["chefEquipe"] == NULL)
                    {
                        ?>
                        <p>//</p>
                    <?php
                    } 
                    else 
                    {
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
                            foreach($membresEquipe as $membre)
                            {
                                $poste = $membre["poste"];
                                ?>
                                <tr>
                                    <td><?=$membre["nom"];?></td>
                                    <td><?=$membre["prenom"];?></td>
                                    <td><?=$poste["nomPoste"];?></td>
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
                                        <th style="width: 31%"><?=$equipe["nomEquipe"];?></th>
                                        <td style="width: 25%"><?=$nbMembreEquipe["UtilisateursParEquipe"];?></td>
                                        <?php
                                        if($equipe["chefEquipe"] == NULL){
                                        ?>
                                            <td style="width: 40%"><div style="margin-left: 5vh" >//</div></td>
                                        <?php
                                        } else {
                                        ?>
                                            <td style="width: 40%"><?=$equipe["chefEquipe"];?></td>
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
                                            <td><div style="margin-left: 5vh">//</div></td>
                                        <?php
                                        } else {
                                            foreach($chefEquipes as $chefEquipe)
                                            {
                                                if($equipe["chefEquipe"] == $chefEquipe["idUtilisateur"])
                                                {
                                                    ?>
                                                    <td><?=$chefEquipe["nom"]. " ". $chefEquipe["prenom"];?> </td>
                                                    <?php
                                                }
                                            }
                                        ?>
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