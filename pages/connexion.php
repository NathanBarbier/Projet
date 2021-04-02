<?php
require_once "entete.php";



if(!empty($_GET["error"]))
{
    ?>
    <div class="alert alert-danger">
    <?php
    switch($_GET["error"]) { case "champsvide":?>
            <?php echo "Erreur : Tous les champs doivent être remplis."?>
            <?php break ?>
        <?php case "idincorrect":?>
            <?php echo "Erreur : L'email n'existe pas."?>
            <?php break ?>
        <?php case "mdpincorrect":?>
            <?php echo "Erreur : Le mot de passe est incorrect."?>
            <?php break ?>
        <?php case "invalidemail":?>
            <?php echo "Erreur : l'email est invalide."?>
            <?php break ?>
        <?php

    }
    ?>
    </div>
    <?php
}
?>


<div class="container mt-5">

    <h1>Connexion</h1>


<form method="post" action="../traitements/connexion.php">

    <div class="form-group">
        <label for="mail" class="ml-3">Addresse mail</label>
        <input type="text" class="form-control" name="mail" placeholder="Saisissez votre identifiant" maxlength="50" required>
    </div>

    <div class="form-group mt-3">
        <label for="mdp" class="ml-3">Mot de passe</label>
        <input type="password" class="form-control" name="mdp" placeholder="Saisissez votre mot de passe" required>
    </div>

    <div class="form-group text-center mt-3">
        <button type="submit" class="btn btn-primary" name="envoi" value="1">Se connecter</button>
    </div>
    <div class="form-group text-center mt-2">
        <div>
            Vous n'avez pas compte ?
        </div>
        <a href="inscriptionOrganisation.php" class="btn btn-info mt-3">Inscrivez-vous</a>
    </div>

</form>
</div>

<?php
require_once "pied.php";
