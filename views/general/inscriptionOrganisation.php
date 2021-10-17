<?php
require_once "layouts/entete.php";

if(!empty($errors)) { ?>
    <div class="position-relative mx-auto">
        <div class="alert alert-danger w-50 text-center position-absolute top-0 start-50 translate-middle-x">
        <?php foreach($errors as $error) { ?>
            <i class="bi bi-exclamation-triangle-fill"></i>
            <?php echo $error . "<br>";
        } ?>
        </div>
    </div>
    <?php } ?>

<form class="w-50 mx-auto" method="POST" action="<?= CONTROLLERS_URL?>general/inscriptionOrganisation.php">
    <input type="hidden" name="action" value="inscriptionOrg">
    <h1 class="mx-auto text-center mt-5" style="border-bottom: rgb(216, 214, 214) 1px solid;">Inscription </h1>

    <div class="form-floating mt-5">
        <input type="text" class="form-control" placeholder="Entrez le nom de votre organisation" id='name' name="name" value="<?= isset($name) ? $name : ''?>" required>
        <label for="name">Nom organisation</label>
    </div>

    <div class="form-floating mt-3">
        <input type="email" class="form-control" placeholder="Entrez l'adresse mail administrateur" id='email' name="email" value="<?= isset($email) ? $email : ''?>" required>
        <label for="email">Adresse email</label>
    </div>

    <div class="form-floating mt-3">
        <input type="password" class="form-control" placeholder="Entrez le mot de passe administrateur" id="pwd" name="pwd" value="<?= isset($pwd2) ? $pwd2 : ''?>" required>
        <label for="pwd">Mot de passe</label>
    </div>

    <div class="form-floating mt-3">
        <input type="password" class="form-control" placeholder="Entrez le mot de passe administrateur" id="pwd2" name="pwd2" value="<?= isset($pwd2) ? $pwd2 : ''?>" required>
        <label for="pwd2">Confirmer mot de passe</label>
    </div>

    <div class="form-check mt-3">
        <input class="form-check-input" name="consent" type="checkbox" id="consent-check" required>
        <label for="consent-check">En cochant cette case vous confirmez accepter le traitement de vos données</label>
    </div>

    <div class="text-center mt-3">
        <button type="submit" class="btn btn-primary mt-3 w-50" name="envoi" value="1">S'inscrire</button>
    </div>

    <div class="form-group text-center mt-2">
        <div class="mt-5">
            Vous possédez un compte ?
        </div>
        <a href="<?= CONTROLLERS_URL ?>general/connexion.php" class="btn btn-info mt-3">Connectez-vous</a>
    </div>
    
</form>

<?php
require_once 'layouts/pied.php';
?>