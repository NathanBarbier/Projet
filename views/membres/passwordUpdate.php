<?php 
require_once "layouts/entete.php";

$data = json_decode(GETPOST('data'));

if($data->success)
{
    ?>
    <div class="alert alert-success">
        Le mot de passe a bien été modifié<br>
    </div>
    <?php
} 
else 
{
    if($data->erreurs)
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
}

// var_dump($data);
?>
    <h1>Modification du mot de passe</h1>

    <a class="btn btn-primary mt-3" href="index.php">Retourner à la vue globale</a>

    <form method="post" action="<?= CONTROLLERS_URL ?>membres/passwordUpdate.php?action=passwordUpdate">
        
        <div class="form-group mt-3">
            <label for="oldmdp">Ancien mot de passe</label>
            <input class="form-control" type="password" required id="oldmdp" name="oldmdp">
        </div>

        <div class="form-group mt-3">
            <label for="newmdp">Nouveau mot de passe</label>
            <input class="form-control" type="password" required id="newmdp" name="newmdp">
        </div>

        <div class="form-group mt-3">
            <label for="newdmp2">Confirmer nouveau mot de passe</label>
            <input class="form-control" type="password" required id="newmdp2" name="newmdp2">
        </div>

        <div class="form-group mt-3">
            <button type="submit" class="btn btn-primary" name="envoi" value="1">Confirmer</button>
        </div>

    </form>

<?php
require_once "layouts/pied.php"; ?>