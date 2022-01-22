<?php
require_once "layouts/entete.php";
?>
    <h1 class="text-center mx-auto w-50 mb-4 border-bottom">Liste des projets</h1>

    <div class="sticker mx-3" style="height:75vh; overflow:auto">
    <?php
    if(count($Organization->getProjects()) > 0)
    { ?>
        <div class="row mx-auto px-2 mt-3 justify-content-between">
            <div class="col-3 sticker text-center"><h5 class="mt-3">Nom</h5></div>
            <div class="col-2 sticker text-center"><h5 class="mt-3">Type</h5></div>
            <div class="col-4 sticker text-center"><h5 class="mt-3">État</h5></div>
            <div class="col-2 sticker text-center"><h5 class="mt-3">Détails</h5></div>
        </div>
        <?php
        foreach($Organization->getProjects() as $Project)
        { ?>
        <div class="row sticker mx-2 mt-4" style="height: 80px;">
            <div class="col-3 text-center pt-4 mx-auto"><b><?= $Project->getName() ?></b></div>
            <div class="col-2 text-center pt-4 mx-auto"><b><?= $Project->getType() ?></b></div>
            <div class="col-4 text-center pt-4 mx-auto"><b><?= $Project->isActive() == 1 ? '<span style="color:green">Ouvert</span>' : '<span style="color:red">Archivé</span>' ?></b></div>
            <div class="col-2 text-center pt-3 mx-auto">
                <a href="<?= CONTROLLERS_URL ?>admin/detailsProjet.php?idProject=<?= $Project->getRowid() ?>" class="btn btn-info mt-1">Détails</a>
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
<?php
require_once "layouts/pied.php";
?>