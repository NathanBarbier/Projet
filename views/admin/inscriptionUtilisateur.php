<?php 
require_once "layouts/entete.php";
?>
    <div class="container">
        <form class="w-50 mx-auto position-relative" method="post" action="<?= CONTROLLERS_URL ?>admin/inscriptionUtilisateur.php?action=signup">
            <?php if($success) { ?>
            <div class="alert alert-success w-100 text-center position-absolute top-0 start-50 translate-middle-x before">
                <i class="bi bi-check-circle-fill"></i>
                <?= $success; ?>
            </div>
            <?php } else if($errors) { ?>
            <div class="alert alert-danger w-100 text-center position-absolute top-0 start-50 translate-middle-x before">
            <?php foreach($errors as $error) { ?>
                <i class="bi bi-exclamation-triangle-fill"></i>
                <?php echo $error . "<br>";
            } ?>
            </div>
            <?php } ?>
            <h1 class="text-center mx-auto w-100 mb-4 border-bottom">Inscription d'un collaborateur</h1>
                
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

            <div class="text-center mt-5">
                <button type="submit" class="btn btn-primary w-50" name="envoi" value="1">Inscrire</button>
            </div>

        </form>
    </div>
</div>
<?php

require_once 'layouts/pied.php';
?>