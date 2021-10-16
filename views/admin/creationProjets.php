<?php
require_once "layouts/entete.php"; 
?>
<div class="col-8 mt-4 pe-4 position-relative">

    <?php if($success) { ?>
        <div class="alert alert-success w-50 text-center position-absolute top-0 start-50 translate-middle-x">
            <i class="bi bi-check-circle-fill"></i>
            <?= $success ?>
        </div>
    <?php } else if ($errors) { ?>
        <div class="alert alert-danger w-50 text-center position-absolute top-0 start-50 translate-middle-x">
    <?php foreach($errors as $error) { ?>
        <i class="bi bi-exclamation-triangle-fill"></i>
        <?php echo $error . "<br>";
    } ?>
        </div>
    <?php } ?>
    <h2 class="mx-auto text-center border-bottom w-50">Création de projet</h2>
    <div class="row mt-4">
            <form method="POST" action="<?= CONTROLLERS_URL ?>admin/creationProjets.php?action=addProjet&idProject=<?= $idProject ?? '' ?>">
                <div class="form-floating mb-3 mt-5 w-50 mx-auto">
                    <input required class="form-control" type="text" name="name" id="name-id" placeholder="Titre du projet" value="<?= $name ?? '' ?>">
                    <label for="name">Titre du projet</label>
                </div>

                <div class="form-floating mb-3 w-50 mx-auto">
                    <input required class="form-control" type="text" name="type" id="type-id" placeholder="Type du projet" value="<?= $type ?? '' ?>">
                    <label for="type">Type du projet</label>
                </div>

                <div class="form-floating mb-3 w-50 mx-auto">
                    <input class="form-control" type="date" name="deadline" id="deadline-id" placeholder="Deadline du projet" value="<?= $deadline ?? '' ?>">
                    <label for="deadline">DeadLine</label>
                </div>

                <div class="form-floating mb-3 w-50 mx-auto">
                    <textarea required class="form-control" name="description" id="description-id" placeholder="Description" maxlength="255"><?= $description ?? '' ?></textarea>
                    <label for="description">Description</label>
                </div>

                <div class="d-flex justify-content-center">
                    <button type="submit" value="<?= true ?>" name="envoi" class="btn btn-outline-primary mt-3 w-50">Créer le projet</button>
                </div>

            </form>
    </div>
</div>

<script type="text/Javascript">
</script>

<script type="text/Javascript" src="<?= JS_URL ?>admin/creationProjets.js"></script>

<?php require_once "layouts/pied.php"; ?>