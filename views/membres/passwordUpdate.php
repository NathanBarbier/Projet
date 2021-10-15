<?php 
require_once "layouts/entete.php";
?>
<div class="col-9 mt-4">
    <div class="position-relative mx-auto">
        <?php if($success) { ?>
        <div class="alert alert-success w-50 text-center position-absolute top-0 start-50 translate-middle-x">
            Le mot de passe a bien été modifié<br>
        </div>
        <?php } else if($errors) {
            ?>
            <div class="alert alert-danger w-50 text-center position-absolute top-0 start-50 translate-middle-x">
                <?php foreach($errors as $error) {
                    echo $error . "<br>";
                } ?>
            </div>
        <?php } ?>
    </div>

    <h1 class="text-center mt-4 w-50 mx-auto" style="border-bottom: rgb(216, 214, 214) solid 1px;">Modification du mot de passe</h1>

    <form class="w-50 mx-auto mt-5" method="post" action="<?= CONTROLLERS_URL ?>membres/passwordUpdate.php?action=passwordUpdate">
        
        <div class="form-floating mt-3">
            <input class="form-control" type="password" required id="oldmdp" name="oldmdp" placeholder="">
            <label for="oldmdp">Ancien mot de passe</label>
        </div>

        <div class="form-floating mt-3">
            <input class="form-control" type="password" required id="newmdp" name="newmdp" placeholder="">
            <label for="newmdp">Nouveau mot de passe</label>
        </div>

        <div class="form-floating mt-3">
            <input class="form-control" type="password" required id="newmdp2" name="newmdp2" placeholder="">
            <label for="newdmp2">Confirmer nouveau mot de passe</label>
        </div>

        <div class="mt-5 text-center">
            <button type="submit" class="w-25 btn btn-outline-primary" name="envoi" value="1" placeholder="">Confirmer</button>
        </div>

    </form>

</div>

<?php
require_once "layouts/pied.php"; ?>