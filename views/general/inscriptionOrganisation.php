<?php
require_once "entete.php";
require_once CONTROLLERS_PATH."InscriptionController.php";

if($erreurs)
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

if($success)
{
    ?>
     <div class="alert alert-success">
     Votre inscription a bien été enrigistrée<br>
     Vous allez être redirigé vers la page de connexion<br>
     Ou cliquez ici pour être redirigé directement <a href="connexion.php"></a>
     </div>
     <?php 
    header("refresh:4;connexion.php");
} 
else
{
?>

<form method="POST" action="inscriptionOrganisation.php">
    <input type="hidden" name="action" value="inscriptionOrg">
    <h1>Inscription</h1>

    <div class="form-group">
        <label for="nom">Nom organisation</label>
        <input type="text" class="form-control" placeholder="Entrez le nom de votre organisation" id='nom' name="nom" value="<?= isset($organisation) ? $organisation : ''?>" required>
    </div>

    <div class="form-group">
        <label for="email">Adresse mail</label>
        <input type="email" class="form-control" placeholder="Entrez l'adresse mail administrateur" id='email' name="email" value="<?= isset($email) ? $email : ''?>" required>
    </div>

    <div class="form-group">
        <label for="mdp">Mot de passe</label>
        <input type="password" class="form-control" placeholder="Entrez le mot de passe administrateur" id="mdp" name="mdp" value="<?= isset($mdp2) ? $mdp2 : ''?>" required>
    </div>

    <div class="form-group">
        <label for="mdp2">Confirmer mot de passe</label>
        <input type="password" class="form-control" placeholder="Entrez le mot de passe administrateur" id="mdp2" name="mdp2" value="<?= isset($mdp2) ? $mdp2 : ''?>" required>
    </div>

    <div class="text-center mt-3">
        <button type="submit" class="btn btn-primary" name="envoi" value="1">S'inscrire</button>
    </div>
    
</form>
<?php
// }

// var_dump($_POST);

require_once 'pied.php';
?>