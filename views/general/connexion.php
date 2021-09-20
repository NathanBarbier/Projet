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
    <h1>Connexion</h1>

    <form method="post" action="<?= CONTROLLERS_URL ?>general/Connexion.php">

        <div class="form-group mt-3">
            <label for="mail" class="mb-1">Addresse mail</label>
            <input type="text" class="form-control" name="email" placeholder="Saisissez votre identifiant" maxlength="50" required>
        </div>

        <div class="form-group mt-3">
            <label for="mdp" class="mb-1">Mot de passe</label>
            <input type="password" class="form-control" name="mdp" placeholder="Saisissez votre mot de passe" required>
        </div>

        <div class="form-group text-center mt-3">
            <button type="submit" class="btn btn-primary" name="envoi" value="1">Se connecter</button>
        </div>
        <div class="form-group text-center mt-2">
            <div>
                Vous n'avez pas de compte ?
            </div>
            <a href="inscriptionOrganisation.php" class="btn btn-info mt-3">Inscrivez-vous</a>
        </div>

    </form>
</div>

<?php
require_once "layouts/pied.php";
