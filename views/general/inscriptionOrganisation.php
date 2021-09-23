<?php
require_once "layouts/entete.php";

if(!empty($erreurs))
{
    ?>
    <div class="alert alert-danger">
    <?php
    foreach($erreurs as $erreur)
    {
        echo $erreur . "<br>";
    }
    ?>
    </div>
    <?php
}

if(!empty($success))
{
    ?>
     <div class="alert alert-success">
     Votre inscription a bien été enregistrée<br>
     Vous allez être redirigé vers la page de connexion<br>
     Ou cliquez ici pour être redirigé directement <a href="connexion.php"></a>
     </div>
     <?php 
    header("refresh:4;".VIEWS_URL."/general/connexion.php");
} 
else
{
?>

<form class="w-50 mx-auto" method="POST" action="<?= CONTROLLERS_URL?>general/inscriptionOrganisation.php">
    <input type="hidden" name="action" value="inscriptionOrg">
    <h1 class="mx-auto text-center mt-5" style="border-bottom: rgb(216, 214, 214) 1px solid;">Inscription </h1>

    <div class="form-floating mt-5">
        <input type="text" class="form-control" placeholder="Entrez le nom de votre organisation" id='nom' name="nom" value="<?= isset($organisation) ? $organisation : ''?>" required>
        <label for="nom">Nom organisation</label>
    </div>

    <div class="form-floating mt-3">
        <input type="email" class="form-control" placeholder="Entrez l'adresse mail administrateur" id='email' name="email" value="<?= isset($email) ? $email : ''?>" required>
        <label for="email">Adresse email</label>
    </div>

    <div class="form-floating mt-3">
        <input type="password" class="form-control" placeholder="Entrez le mot de passe administrateur" id="mdp" name="mdp" value="<?= isset($mdp2) ? $mdp2 : ''?>" required>
        <label for="mdp">Mot de passe</label>
    </div>

    <div class="form-floating mt-3">
        <input type="password" class="form-control" placeholder="Entrez le mot de passe administrateur" id="mdp2" name="mdp2" value="<?= isset($mdp2) ? $mdp2 : ''?>" required>
        <label for="mdp2">Confirmer mot de passe</label>
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
}
require_once 'layouts/pied.php';
?>