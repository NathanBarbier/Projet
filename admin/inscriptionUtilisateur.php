<?php require_once "entete.php";
?>
<div class="col-10 container mt-5">

<form method="post" action="../traitements/inscriptionUtilisateur.php">
        
    <div class="form-group">
        <label for="email">Adresse mail</label>
        <input class="form-control" type="email" name="email" id="email" value="<?= isset($email) ? $email : ""?>"  required>
    </div>

    <div class="form-group">
        <label for="nom">Nom</label>
        <input class="form-control" type="nom" name="nom" id="nom" value="<?= isset($nom) ? $nom : ""?>"  required>
    </div>

    <div class="form-group">
        <label for="prenom">Prénom</label>
        <input class="form-control" type="prenom" name="prenom" id="prenom" value="<?= isset($prenom) ? $prenom : ""?>"  required>
    </div>
    
    <div class="form-group">
        <label for="dateNaiss">Date de naissance</label>
        <input class="form-control" type="date" name="dateNaiss" id="dateNaiss" placeholder="AAAA-MM-JJ" value="<?= isset($dateNaiss) ? $dateNaiss : ""?>"  required>
    </div>

    <div class="row text-center ">
        <div class="col" style="width:40%;">
            <label for="idPoste">Sélectionnez un poste</label>
            <select class="form-control col text-center" name="idPoste" id="idPoste">
                <?php
                    // récupérer postes de l'organisation
                    $nomPostes = recupererPostes($_SESSION["idOrganisation"]);
                    
                    foreach($nomPostes as $nomPoste)
                    {
                        ?>
                        <option value="<?= $nomPoste["idPoste"] ?>"><?= $nomPoste["nomPoste"] ?></option>
                        <?php
                    }
                    
                    ?>
            </select>
        </div>
        <div class="col" style="width:40%">
            <label for="idEquipe">Sélectionnez une équipe</label>
            <select class="form-control col text-center" name="idEquipe" id="idEquipe">
                <?php
                    // récupérer équipes de l'organisation
                    $nomEquipes = recupererEquipes($idOrganisation);
                    
                    foreach($nomEquipes as $nomEquipe)
                    {
                        ?>
                        <option value="<?= $nomEquipe["idEquipe"] ?>"><?= $nomEquipe["nomEquipe"] ?></option>
                        <?php
                    }
                        ?>
            </select>
        </div>
    
    
    </div>
    <div class="text-center mt-5">
        <button style="min-width: 15%" type="submit" class="btn btn-primary" name="envoi" value="1">Inscrire</button>
    </div>


</form>

<?php
require_once 'pied.php' ?>