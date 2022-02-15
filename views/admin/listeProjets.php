<?php
require_once "layouts/entete.php";
?>
    <div class="row position-relative">
        <?php if($errors) { ?>
        <div class="before alert alert-danger mt-3 w-50 text-center position-absolute top-0 start-50 translate-middle-x">
        <?php foreach($errors as $error) { ?>
            <i class="bi bi-exclamation-triangle-fill"></i>
            <?php echo $error . "<br>";
        } ?>
        </div>
        <?php } else if ($success) { ?>
        <div class="before alert alert-success mt-3 w-50 text-center position-absolute top-0 start-50 translate-middle-x">
            <i class="bi bi-check-circle-fill"></i>
            <?= $success; ?>
        </div>
        <?php } ?>
    </div>

    <h1 class="text-center mx-auto w-50 mb-3 pb-2 bg-white underline sticker">Liste des projets</h1>

    <div id="del-project-confirmation" class="sticker mb-3 py-3 px-3 mx-3 text-center collapse" style="height: max-content;">
        <h4 class="mx-auto border-bottom w-75 mb-3">Confirmation de suppression de projet.</h4>
        <b>Êtes-vous sûr de vouloir supprimer le projet ?</b>
        <br>
        (Cette action est définitive et supprimera toute donnée étant en lien avec celui-ci)
        <div class="mt-4 row">
            <div class="col-6 col-sm-12 mb-0 mb-sm-2 mb-md-0 col-md-6 text-end">
                <a id="delete-project-btn-conf" class="w-100 btn btn-outline-danger double-button-responsive" href="<?= CONTROLLERS_URL ?>admin/listeProjets.php?action=deleteProject">Supprimer</a>
            </div>
            <div class="col-6 col-sm-12 col-md-6 text-start">
                <button id="cancel-delete-btn" class="w-100 btn btn-outline-warning double-button-responsive">Annuler</button>
            </div>
        </div>
    </div>

    <div class="sticker mx-3" style="height:75vh; overflow:auto">
    <?php
    if(count($Organization->getProjects()) > 0)
    { ?>
        <div class="row mx-auto px-2 mt-3 justify-content-between">
            <div class="col-3 sticker text-center"><h5 class="mt-3">Nom</h5></div>
            <div class="col-2 sticker text-center"><h5 class="mt-3">Type</h5></div>
            <div class="col-3 sticker text-center"><h5 class="mt-3">État</h5></div>
            <div class="col-3 sticker text-center"><h5 class="mt-3">Options</h5></div>
        </div>
        <?php
        foreach($Organization->getProjects() as $Project)
        { ?>
        <div class="row sticker mx-2 mt-4" style="height: 80px;">
            <div class="col-3 text-center pt-4 mx-auto"><b><?= $Project->getName() ?></b></div>
            <div class="col-2 text-center pt-4 mx-auto"><b><?= $Project->getType() ?></b></div>
            <div class="col-3 text-center pt-4 mx-auto"><b><?= $Project->isActive() == 1 ? '<span style="color:green">Ouvert</span>' : '<span style="color:red">Archivé</span>' ?></b></div>
            <div class="col-3 text-center pt-3 mx-auto">
                <a href="<?= CONTROLLERS_URL ?>admin/detailsProjet.php?idProject=<?= $Project->getRowid() ?>" class="btn btn-info btn-sm mt-1">
                    Détails
                </a>
                <input type="hidden" class="project-id" value="<?= $Project->getRowid() ?>">
                <button class="del-project-btn btn btn-outline-danger btn-sm mt-1">
                    Supprimer
                </button>
            </div>
        </div>
        <?php }
    } 
    else 
    { ?>
        <div class="sticker w-75 mx-auto text-center mt-4">
            <h3 class="mt-2">Votre organisation n'a aucun projet.</h3>
        </div>
    <?php } ?>
    </div>

    <script type="text/Javascript" src="<?= JS_URL ?>admin/listeProjets.min.js" defer></script>
<?php
require_once "layouts/pied.php";
?>