<?php 
require_once "layouts/entete.php";
// require_once CONTROLLERS_PATH."Inscription.php";
?>
<div class="col-9 container mt-5">

<h1>Inscription d'un collaborateur</h1>

<div class="col-9 mt-4">
    <div class="container">
        <form method="post" action="inscriptionUtilisateur.php?action=signup">
                
            <div class="form-floating mt-3 mb-3">
                <input class="form-control" type="email" name="email" id="email" placeholder="adresse email" value="<?= isset($email) ? $email : ""?>"  required>
                <label for="email">Adresse email</label>
            </div>

            <div class="form-floating mb-3">
                <input class="form-control" type="text" name="nom" id="nom" placeholder="Nom" value="<?= isset($nom) ? $nom : ""?>"  required>
                <label for="nom">Nom</label>
            </div>

            <div class="form-floating mb-3">
                <input class="form-control" type="text" name="prenom" id="prenom" placeholder="Prénom" value="<?= isset($prenom) ? $prenom : ""?>"  required>
                <label for="prenom">Prénom</label>
            </div>
            
            <div class="form-floating mb-3">
                <input class="form-control" type="date" name="birth" id="birth" placeholder="AAAA-MM-JJ" value="<?= isset($birth) ? $birth : ""?>"  required>
                <label for="dateNaiss">Date de naissance</label>
            </div>

            <div class="row text-center ">
                <div class="col" style="width:40%;">
                    <label for="idPoste">Sélectionnez un poste</label>
                    <select class="form-control col text-center" name="idPoste" id="idPoste">
                        <?php
                            foreach($postes as $poste)
                            {?>
                                <option value="<?= $nomPoste["idPoste"] ?>"><?= $nomPoste["nomPoste"] ?></option>
                            <?php
                            } ?>
                    </select>
                </div>
                <div class="col" style="width:40%">
                    <label for="idEquipe">Sélectionnez une équipe</label>
                    <select class="form-control col text-center" name="idEquipe" id="idEquipe">
                        <?php                      
                            foreach($equipes as $equipe)
                            { ?>
                                <option value="<?= $nomEquipe["idEquipe"] ?>"><?= $nomEquipe["nomEquipe"] ?></option>
                            <?php
                            } ?>
                    </select>
                </div>
            
            
            </div>
            <div class="text-center mt-5">
                <button style="min-width: 15%" type="submit" class="btn btn-primary" name="envoi" value="1">Inscrire</button>
            </div>

        </form>
    </div>
</div>
<?php
require_once 'layouts/pied.php' ?>