<?php
require_once "layouts/entete.php";
?>

<div class="container">
    <div class="row">
        <!-- <form class="col-md-12 col-lg-6 mx-auto border-lg bg-white px-4 pb-3" method="post" action="<?= CONTROLLERS_URL ?>visiteur/connexion.php"> -->
        <form id="connexion-form" class="col-md-12 col-lg-6 mx-auto border-lg bg-white px-4 pb-3" method="POST">
            <h1 class="text-center w-100 mx-auto border-bottom mt-2 underline">Connexion</h1>
    
            <div class="form-floating mt-5">
                <input id="email" type="email" class="form-control" name="email" placeholder=" " value="<?= $email ?? '' ?>" maxlength="50" required>
                <label for="email" class="mb-1">Adresse email</label>
            </div>
    
            <div class="form-floating mt-3">
                <input id="password" type="password" class="form-control" name="password" placeholder=" " required>
                <label for="password" class="mb-1">Mot de passe</label>
            </div>
            
            <div class="form-check mt-3">
                <input class="form-check-input" name="rememberMe" value="1" type="checkbox" id="rememberMe">
                <label class="form-check-label" for="rememberMe">Se souvenir de moi</label>
            </div>
            
            <div class="form-group text-center mt-3">
                <button id="connexion-form-button" type="submit" class="custom-button mt-3 w-100" name="envoi" value="1">Se connecter</button>
            </div>

            <div class="text-center mt-2">
                <div class="mt-5">
                    Vous n'avez pas de compte ?
                </div>
                <div class="row">
                    <div class="col-6 col-sm-6 col-md-5 col-lg-6 col-xl-5 mx-auto">
                        <a href="inscriptionOrganisation.php" class="w-100 custom-button info pt-2 px-1 mt-3">Inscrivez-vous</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script type="text/Javascript" src="<?= JS_URL ?>visiteur/connexion.js" defer></script>

<?php
require_once "layouts/pied.php";
