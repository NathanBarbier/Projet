<?php
require_once "layouts/entete.php";
?>
<div class="container">

    <div class="row">
        <form class="col-md-12 col-lg-6 mx-auto border-lg bg-white px-4 pb-3" method="POST" action="<?= CONTROLLERS_URL?>visiteur/inscriptionOrganisation.php">
            <input type="hidden" name="action" value="inscriptionOrg">
            <h1 class="mx-auto text-center mt-2 underline" style="border-bottom: rgb(216, 214, 214) 1px solid;">Inscription </h1>
        
            <div class="form-floating mt-4">
                <input type="text" class="form-control" placeholder=" " id='name' name="name" value="<?= isset($name) ? $name : ''?>" required>
                <label for="name">Nom organisation</label>
            </div>
        
            <div class="form-floating mt-3">
                <input type="email" class="form-control" placeholder=" " id='email' name="email" value="<?= isset($email) ? $email : ''?>" required>
                <label for="email">Adresse email</label>
            </div>
        
            <div class="form-floating mt-3">
                <input type="password" class="form-control" placeholder=" " id="pwd" name="pwd" value="<?= isset($pwd2) ? $pwd2 : ''?>" required>
                <label for="pwd">Mot de passe</label>
            </div>
        
            <div class="form-floating mt-3">
                <input type="password" class="form-control" placeholder=" " id="pwd2" name="pwd2" value="<?= isset($pwd2) ? $pwd2 : ''?>" required>
                <label for="pwd2">Confirmer mot de passe</label>
            </div>
        
            <div class="form-check mt-3">
                <input class="form-check-input" name="consent" value="1" type="checkbox" id="consent-check" required>
                <label class="form-check-label" for="consent-check">En cochant cette case vous acceptez nos <a class="custom-link" href="<?= VIEWS_URL ?>visiteur/cgu.php" target="_blank">Conditions générales</a> et confirmez accepter le traitement de vos données</label>
            </div>

            <div class="text-center mt-3">
                <button type="submit" class="custom-button mt-3 w-100" name="envoi" value="1">S'inscrire</button>
            </div>
            
            <div class="form-group text-center mt-2">
                <div class="mt-4">
                    Vous possédez un compte ?
                </div>
                <div class="row">
                    <div class="col-6 col-sm-6 col-md-5 col-lg-6 col-xl-5 mx-auto">
                        <a href="<?= CONTROLLERS_URL ?>visiteur/connexion.php" class="w-100 custom-button info pt-2 px-1 mt-3">Connectez-vous</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<?php
require_once 'layouts/pied.php';
?>