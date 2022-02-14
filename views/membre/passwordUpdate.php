<?php 
require_once "layouts/entete.php";
?>
<div class="col-12 mt-4">
    <div class="position-relative mx-auto">
            <?php  if($errors) { ?>
            <div class="alert alert-danger w-50 text-center position-absolute top-0 start-50 translate-middle-x">
                <?php foreach($errors as $error) { ?>
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    <?php echo $error . "<br>";
                } ?>
            </div>
        <?php } ?>
    </div>

    <div class="row px-3">
        <div class="col-md-9 col-lg-6 mx-auto border-lg bg-white px-4 pb-3">
            <h1 class="text-center mt-4 w-75 mx-auto underline pb-3">Modification du mot de passe</h1>
            <hr class="w-75 mx-auto">
            <form class="mt-4" method="post" action="<?= CONTROLLERS_URL ?>membre/passwordUpdate.php?action=passwordUpdate">
                <div class="form-floating w-100 mx-auto">
                    <input class="form-control" type="password" required id="oldmdp" name="oldmdp" placeholder="Ancien mot de passe">
                    <label for="oldmdp">Ancien mot de passe</label>
                </div>
        
                <div class="form-floating mt-3 w-100 mx-auto">
                    <input class="form-control" type="password" required id="newmdp" name="newmdp" placeholder="Nouveau mot de passe">
                    <label for="newmdp">Nouveau mot de passe</label>
                </div>
        
                <div class="form-floating mt-3 w-100 mx-auto">
                    <input class="form-control" type="password" required id="newmdp2" name="newmdp2" placeholder="Nouveau mot de passe">
                    <label for="newdmp2">Nouveau mot de passe</label>
                </div>
        
                <div class="mt-5 text-center">
                    <button type="submit" class="w-100 btn btn-outline-classic" name="envoi" value="1" placeholder="">Confirmer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
require_once "layouts/pied.php"; ?>