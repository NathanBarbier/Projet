<?php
require_once "layouts/entete.php";
?>

    <h1 class="text-center mx-auto w-50 mb-3 pb-2 bg-white underline sticker">Liste des projets</h1>

    <div class="row px-4">
        <div id="del-project-confirmation" class="sticker p-3 mb-3 mx-auto text-center collapse col-12 col-md-6" style="height: max-content;">
            <h4 class="mx-auto border-bottom w-75 mb-3">Confirmation de suppression de projet.</h4>
            <b>Êtes-vous sûr de vouloir supprimer le projet ?</b>
            <br>
            (Cette action est définitive et supprimera toute donnée étant en lien avec celui-ci)
            <div class="mt-4 row">
                <div class="col-6">
                    <a id="delete-project-btn-conf" class="w-100 pt-2 custom-button danger double-button-responsive" href="<?= CONTROLLERS_URL ?>admin/listeProjets.php?action=deleteProject">Supprimer</a>
                </div>
                <div class="col-6">
                    <button id="cancel-delete-btn" class="w-100 custom-button warning double-button-responsive">Annuler</button>
                </div>
            </div>
        </div>
    </div>

    <div class="sticker mx-3" style="height:85vh; overflow:auto">
    <?php
    if(count($Organization->getProjects()) > 0)
    { ?>
        <div class="row mx-auto px-2 mt-3 justify-content-between">
            <div class="col-3 sticker text-center"><h5 class="mt-3">Nom</h5></div>
            <div class="col-2 sticker text-center"><h5 class="mt-3">Type</h5></div>
            <div class="col-3 sticker text-center"><h5 class="mt-3">État</h5></div>
            <div class="col-3 sticker text-center"><h5 class="mt-3">Options</h5></div>
        </div>

        <div id="projects-container" class="pb-3">
            <?php
            foreach($Organization->getProjects() as $Project)
            { ?>
            <div class="row sticker mx-2 mt-4 pb-2 h-auto d-flex align-items-center">
                <div class="col-3 text-center mx-auto"><b><?= $Project->getName() ?></b></div>
                <div class="col-2 text-center mx-auto"><b><?= $Project->getType() ?></b></div>
                <div class="col-3 text-center mx-auto"><b><?= $Project->isActive() == 1 ? '<span style="color:green">Ouvert</span>' : '<span style="color:red">Archivé</span>' ?></b></div>
                <div class="col-3 text-center mx-auto">
                    
                    <input type="hidden" class="project-id" value="<?= $Project->getRowid() ?>">
                    
                    <div class="row">
                        <div class="col-12 col-lg-6">
                            <a href="<?= CONTROLLERS_URL ?>admin/detailsProjet.php?idProject=<?= $Project->getRowid() ?>" class="w-100 custom-button info btn-sm mt-1 px-1 pt-2 double-button-responsive">
                                Détails
                            </a>
                        </div>
                        <div class="col-12 col-lg-6">
                            <button class="w-100 del-project-btn custom-button danger btn-sm mt-1 px-1 double-button-responsive">
                                Supprimer
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <?php 
            } ?>

            <div id="load-more-line" class="radius text-center mx-auto mt-2 border" style="height: 5vh;width:33%;font-size: x-large">
                <a id="load-more" type="button" class="custom-link py-0">Load more</a>
            </div>
        </div>
        <?php
    } 
    else 
    { 
    ?>
        <div class="sticker w-75 mx-auto text-center mt-4">
            <h3 class="mt-2">Votre organisation n'a aucun projet.</h3>
        </div>
    <?php
    } 
    ?>
    </div>

    <script type="text/Javascript" src="<?= JS_URL ?>admin/listeProjets.js" defer></script>
<?php
require_once "layouts/pied.php";
?>