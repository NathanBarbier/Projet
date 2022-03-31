<?php 
require_once "layouts/header.php";
?>
<div class="col-12 mt-4">

    <div class="row px-3">
        <div class="col-md-9 col-lg-6 mx-auto border-lg bg-white px-4 pb-3">
            <h1 class="text-center mt-4 w-75 mx-auto underline pb-3">Modification du mot de passe</h1>
            <hr class="w-75 mx-auto">
            <form class="mt-4" method="post" action="<?= CONTROLLERS_URL ?>member/passwordUpdate.php?action=passwordUpdate">
                <div class="form-floating w-100 mx-auto">
                    <input class="form-control" type="password" required id="oldmdp" name="oldmdp" placeholder=" ">
                    <label for="oldmdp">Ancien mot de passe</label>
                </div>
        
                <div class="form-floating mt-3 w-100 mx-auto">
                    <input class="form-control" type="password" required id="newmdp" name="newmdp" placeholder=" ">
                    <label for="newmdp">Nouveau mot de passe</label>
                </div>
        
                <div class="form-floating mt-3 w-100 mx-auto">
                    <input class="form-control" type="password" required id="newmdp2" name="newmdp2" placeholder=" ">
                    <label for="newdmp2">Nouveau mot de passe</label>
                </div>
        
                <div class="mt-5 text-center">
                    <button type="submit" class="w-100 custom-button" name="envoi" value="1" placeholder="">Confirmer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
require_once "layouts/footer.php"; ?>