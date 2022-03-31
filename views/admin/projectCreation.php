<?php
require_once "layouts/header.php"; 
?>
    <div class="row mt-4 px-3">
        <div class="col-md-10 col-lg-6 mx-auto border-lg bg-white px-4 pb-3">
            <h2 class="mx-auto text-center mt-2 w-50 underline">Création de projet</h2>
            <hr class="w-75 mx-auto">
            <form method="POST" action="<?= CONTROLLERS_URL ?>admin/projectCreation.php?action=addProjet&idProject=<?= $idProject ?? '' ?>">
                <div class="form-floating mb-3 mt-5 w-75 mx-auto">
                    <input required class="form-control" type="text" name="name" id="name-id" placeholder=" " value="<?= $name ?? '' ?>">
                    <label for="name">Titre du projet</label>
                </div>
    
                <div class="form-floating mb-3 w-75 mx-auto">
                    <input required class="form-control" type="text" name="type" id="type-id" placeholder=" " value="<?= $type ?? '' ?>">
                    <label for="type">Type du projet</label>
                </div>
    
                <div class="form-floating mb-3 w-75 mx-auto">
                    <textarea required class="form-control" name="description" id="description-id" placeholder=" " maxlength="255"><?= $description ?? '' ?></textarea>
                    <label for="description">Description</label>
                </div>
    
                <div class="d-flex justify-content-center">
                    <button type="submit" value="<?= true ?>" name="envoi" class="custom-button mt-3 w-75">Créer le projet</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/Javascript" src="<?= JS_URL ?>admin/creationProjets.min.js" defer></script>

<?php require_once "layouts/footer.php"; ?>