<?php 
require_once "layouts/entete.php";

$data = json_decode(GETPOST('data'));

?>

<div class="col-9 container mt-5">

<?php
if($data->success)
{
    ?>
    <div class="alert alert-success">
    <?php
        echo $data->success;
    ?>
    </div>
    <?php
}
else if($data->erreurs)
{
    ?>
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

<h1>Inscription d'un collaborateur</h1>

<div class="col-9 mt-4">
    <div class="container">
        <form method="post" action="<?= CONTROLLERS_URL ?>admin/inscriptionUtilisateur.php?action=signup">
                
            <div class="form-floating mt-3 mb-3">
                <input class="form-control" type="email" name="email" id="email" placeholder="adresse email" value="<?= $data->email ?? ""?>"  required>
                <label for="email">Adresse email</label>
            </div>

            <div class="form-floating mb-3">
                <input class="form-control" type="text" name="lastname" id="lastname" placeholder="Nom" value="<?= $data->lastname ?? ""?>"  required>
                <label for="nom">Nom</label>
            </div>

            <div class="form-floating mb-3">
                <input class="form-control" type="text" name="firstname" id="firstname" placeholder="Prénom" value="<?= $data->firstname ?? ""?>"  required>
                <label for="prenom">Prénom</label>
            </div>
            
            <div class="form-floating mb-3">
                <input class="form-control" type="date" name="birth" id="birth" placeholder="AAAA-MM-JJ" value="<?= $data->birth ?? ""?>"  required>
                <label for="dateNaiss">Date de naissance</label>
            </div>

            <div class="row text-center ">
                <div class="col" style="width:40%;">
                    <label for="idPoste">Sélectionnez un poste</label>
                    <select class="form-control col text-center" name="idPoste" id="idPoste">
                        <?php
                            if($data->postes)
                            {
                                foreach($data->postes as $poste)
                                {?>
                                    <option value="<?= $poste->idPoste ?>"><?= $poste->nomPoste ?></option>
                                <?php
                                }
                            } ?>
                    </select>
                </div>
                <div class="col" style="width:40%">
                    <label for="idEquipe">Sélectionnez une équipe</label>
                    <select class="form-control col text-center" name="idEquipe" id="idEquipe">
                        <?php    
                        if($data->equipes)
                        {
                            foreach($data->equipes as $equipe)
                            { ?>
                                <option value="<?= $equipe->idEquipe ?>"><?= $equipe->nomEquipe ?></option>
                            <?php
                            }
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

require_once 'layouts/pied.php';
?>