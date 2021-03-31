<?php
require_once "entete.php"

if(!empty($_GET["error"]))
{
    ?>
    <div class="alert alert-danger">
    <?php
    switch($_GET["error"]) { case "champsvide":?>
            <?php echo "Erreur : Tous les champs doivent être remplis."?>
            <?php break ?>
        <?php case "nonidentiques":?>
            <?php echo "Erreur : Les mots de passe ne sont pas identiques."?>
            <?php break ?>
        <?php case "nomindisponible":?>
            <?php echo "Erreur : Le nom est indisponible."?>
            <?php break ?>
        <?php case "emailincorrect":?>
            <?php echo "Erreur : L'Email n'est pas correct."?>
            <?php break ?>
        <?php case "emailindisponible":?>
            <?php echo "Erreur : L'Email est indisponible."?>
            <?php break ?>
        <?php case "fatalerror":?>
            <?php echo "Erreur : l'inscription n'a pas pu aboutir."?>
            <?php break ?>
        <?php
    }
    ?>
    </div>
    <?php
}

if(!empty($_GET["success"]))
{
    ?>
    <div class="alert alert-success">
    Votre incription a bien été enrigistrée<br>
    Vous allez être redirigé vers la page de connexion<br>
    Ou cliquez ici pour être redirigé directement <a href="connexion.php"></a>
    </div>
    <?php
    header("refresh:4;connexion.php");
} else {
?>

<form method="post" action="../traitements/inscriptionOrganisation.php">
    <h1>Inscription</h1>

    <div class="form-group">
        <label for="organisation">Nom organisation</label>
        <input type="text" class="form-control" placeholder="Entrez le nom de votre organisation" id='organisation' name="organisation" value="<?= isset($organisation) ? $organisation : ''?>" required>
    </div>

    <div class="form-group">
        <label for="mail">Adresse mail</label>
        <input type="email" class="form-control" placeholder="Entrez l'adresse mail administrateur" id='mail' name="mail" value="<?= isset($mail) ? $mail : ''?>" required>
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
}

require_once 'pied.php'
?>