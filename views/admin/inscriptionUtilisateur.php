<?php 
require_once "layouts/entete.php";
?>

<div class="col-9 mt-3">
    <div class="container">

<?php
if($success)
{
    ?>
    <div class="alert alert-success">
    <?php
        echo $success;
    ?>
    </div>
    <?php
}
else if($errors)
{
    ?>
    <div class="alert alert-danger">
    <?php
    foreach($errors as $error)
    {
        echo $error . "<br>";
    }
    ?>
    </div>
    <?php
}
?>
        <h1>Inscription d'un collaborateur</h1>
        <form method="post" action="<?= CONTROLLERS_URL ?>admin/inscriptionUtilisateur.php?action=signup">
                
            <div class="form-floating mt-3 mb-3">
                <input class="form-control" type="email" name="email" id="email" placeholder="adresse email" value="<?= $email ?? ""?>"  required>
                <label for="email">Adresse email</label>
            </div>

            <div class="form-floating mb-3">
                <input class="form-control" type="text" name="lastname" id="lastname" placeholder="Nom" value="<?= $lastname ?? ""?>"  required>
                <label for="nom">Nom</label>
            </div>

            <div class="form-floating mb-3">
                <input class="form-control" type="text" name="firstname" id="firstname" placeholder="Prénom" value="<?= $firstname ?? ""?>"  required>
                <label for="prenom">Prénom</label>
            </div>
            
            <div class="form-floating mb-3">
                <input class="form-control" type="date" name="birth" id="birth" placeholder="AAAA-MM-JJ" value="<?= $birth ?? ""?>"  required>
                <label for="dateNaiss">Date de naissance</label>
            </div>

            <div class="row text-center ">
                <div class="col-6 mx-auto">
                    <label for="idPosition">Sélectionnez un poste</label>
                    <select class="form-control col text-center mt-3" name="idPosition" id="idPosition">
                        <?php
                            foreach($positions as $position)
                            {
                                ?>
                                <option value="<?= $position->rowid ?>"><?= $position->name ?></option>
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

require_once 'layouts/pied.php';
?>