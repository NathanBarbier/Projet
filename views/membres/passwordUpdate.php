<?php 
require_once VIEWS_PATH."/membres/entete.php";
require_once CONTROLLERS_PATH."UserController.php";

if($success)
{
    ?>
    <div class="alert alert-success">
    Le mot de passe a bien été changé<br>
    Vous allez être redirigé vers la vue globale<br>
    <a href="index.php">Cliquez ici si vous ne voulez pas attendre</a>
    </div>
    <?php
} 
else 
{
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
}
?>
    <h1>Modification du mot de passe</h1>

    <a class="btn btn-primary" href="index.php">Retourner à la vue globale</a>

    <form method="post" action="passwordUpdate.php">
        
        <div class="form-group">
            <label for="oldmdp">Ancien mot de passe</label>
            <input class="form-control" type="password" required id="oldmdp" name="oldmdp">
        </div>

        <div class="form-group">
            <label for="newmdp">Nouveau mot de passe</label>
            <input class="form-control" type="password" required id="newmdp" name="newmdp">
        </div>

        <div class="form-group">
            <label for="newdmp2">Confirmer nouveau mot de passe</label>
            <input class="form-control" type="password" required id="newmdp2" name="newmdp2">
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-primary" name="envoi" value="1">Confirmer</button>
        </div>

    </form>

<?php
require_once "pied.php"; ?>