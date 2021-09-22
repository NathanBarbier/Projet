<?php
require_once "layouts/entete.php";

$data = !empty($_GET["data"]) ? json_decode($_GET["data"]) : null;

if(!empty($data->erreurs))
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


<div class="container mt-5">
    <h1 class="text-center w-50 mx-auto" style="border-bottom: rgb(216, 214, 214) 1px solid;">Connexion</h1>

    <form class="w-50 mx-auto" method="post" action="<?= CONTROLLERS_URL ?>general/Connexion.php">

        <div class="form-floating mt-5">
            <input type="text" class="form-control" name="email" placeholder="Saisissez votre identifiant" value="<?= $data->email ?? '' ?>" maxlength="50" required>
            <label for="mail" class="mb-1">Addresse mail</label>
        </div>

        <div class="form-floating mt-3">
            <input type="password" class="form-control" name="mdp" placeholder="Saisissez votre mot de passe" required>
            <label for="mdp" class="mb-1">Mot de passe</label>
        </div>

        <div class="form-group text-center mt-3">
            <button type="submit" class="btn btn-primary w-50 mt-3" name="envoi" value="1">Se connecter</button>
        </div>
        <div class="form-group text-center mt-2">
            <div class="mt-5">
                Vous n'avez pas de compte ?
            </div>
            <a href="inscriptionOrganisation.php" class="btn btn-info mt-3">Inscrivez-vous</a>
        </div>

    </form>
</div>

<?php
require_once "layouts/pied.php";
