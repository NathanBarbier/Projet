<?php 
require_once "layouts/header.php";
?>
<div class="row position-relative bg-white" style="height: 100%;">

    <?php if (!$Project->isActive()) { ?>
        <div class="alert alert-info alert-visible mt-3 w-50 text-center position-absolute top-0 start-50 translate-middle-x collapse show" style="z-index: 1;">
            <i class="bi bi-info-circle-fill"></i>    
            Ce projet est archivé.
            &nbsp;&nbsp;<a href="<?= CONTROLLERS_URL ?>admin/map.php?action=openProject&projectId=<?= $Project->getRowid() ?>&teamId=<?= $teamId ?>" class="btn btn-outline-secondary">Ré-ouvrir</a>
            <button type="button" class="close-alert btn-close position-absolute top-0 end-0 me-4 mt-3" aria-label="Close"></button>
            <span class="notificationCount position-absolute top-0 start-100 translate-middle badge rounded-pill bg-dark">
                <?= $notificationCount . "+" ?>
            </span>
        </div>
    <?php } ?>
    
    <?php if (!$Team->isActive()) { ?>
        <div class="alert alert-info alert-visible mt-3 w-50 text-center position-absolute top-0 start-50 translate-middle-x collapse show before">
            <i class="bi bi-info-circle-fill"></i>    
            Ce tableau est archivé.
            &nbsp;&nbsp;<a href="<?= CONTROLLERS_URL ?>admin/map.php?action=openTeam&projectId=<?= $Project->getRowid() ?>&teamId=<?= $teamId ?>" class="btn btn-outline-secondary">Ré-ouvrir</a>
            <button type="button" class="close-alert btn-close position-absolute top-0 end-0 me-4 mt-3" aria-label="Close"></button>
            <span class="notificationCount position-absolute top-0 start-100 translate-middle badge rounded-pill bg-dark">
                <?= $notificationCount . "+" ?>
            </span>
        </div>
    <?php } ?>

    <!-- Archived tasks Modal -->
    <div class="modal" id="archive-tasks-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog position-absolute start-50 translate-middle w-75" style="top:40%; height:75vh">
            <div class="modal-content" style="height: inherit;">
                <div class="modal-body position-relative pt-0">
                    <i id="close-tasks-modal" class="bi bi-x btn btn-outline-danger position-absolute end-0 top-0 mt-2 me-2" style="width: auto;"></i>
                    <div class="row text-center mt-2">
                        <h4 class="underline">Tâches archivées</h4>
                        <hr class="mx-auto mt-2 mb-0">
                        
                        <div id="archived-tasks-container" class="overflow-y mt-3" style="height: 60vh;">
                            <?php
                            foreach($Team->getMapColumns() as $Column) 
                            {
                                if(!empty($Column->getTasks())) 
                                {
                                    foreach($Column->getTasks() as $Task) 
                                    {
                                        if(!$Task->isActive()) 
                                        { ?>
                                            <div class="row radius hover w-100 mx-0 mt-3 align-content-center border task-line" style="height: 100px;">
                                                <div class="col-8 d-flex align-content-center">
                                                    <div class="w-100 h-100">
                                                        <?= $Task->getName() ?>
                                                    </div>
                                                </div>
                                                <div class="col-4 align-content-center">        
                                                    <input type="hidden" name="task-id" value="<?= $Task->getRowid() ?>">
                                                    <i class="bi bi-archive-fill btn btn-outline-success w-100 mb-2 open-task-btn"></i>
                                                </div>
                                            </div>
                                        <?php 
                                        }
                                    }
                                }
                            } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Back Page -->
    <a href="<?= CONTROLLERS_URL ?>admin/projectDashboard.php?idProject=<?= $Project->getRowid() ?>" ><i class="btn btn-outline-dark bi bi-box-arrow-left position-absolute start-0 top-0 mt-2 me-2 w-auto" style="z-index: 1;"></i></a>
    <!-- Expand right section -->
    <i id="open-right-section" class="btn btn-outline-dark bi bi-arrow-bar-left position-absolute end-0 top-0 mt-2 me-2 w-auto collapse"></i>

    <div id="archive-confirmation" class="collapse mt-3">
        <h3 class="mx-auto text-center w-50 underline">Archiver le tableau</h3>

        <div class="sticker h-auto w-50 mx-auto text-center mt-3 pb-5 px-3">
            <p class="mt-5 mb-0"><b>Êtes-vous sûr de vouloir archiver le tableau ?</b></p>
	    <p><b>(vous pourrez le ré-ouvrir plus tard)</b></p>
            <a href="<?= CONTROLLERS_URL ?>admin/map.php?action=archiveTeam&projectId=<?= $projectId ?>&teamId=<?= $teamId ?>" class="btn btn-outline-success w-50 mt-5">Archiver le tableau</a>
            <a id="cancel-archive" class="btn btn-outline-danger w-50 mt-3">Annuler</a>
        </div>
    </div>

    <div id="left-section" class="col-sm-8 col-md-9 col-lg-10 mt-2 ps-3">
        <div class="collapse show position-relative">
            <i id="close-details" class="btn btn-outline-dark bi bi-arrow-bar-right position-absolute end-0 top-0 w-auto collapse show"></i>
            <div id="columns-container" class="ms-3 overflow-x d-flex" style="height: 98%;">
                <?php foreach($Team->getMapColumns() as $columnKey => $Column) { ?>
                    <div class="project-column">
                        <input class="columnId-input" type="hidden" value="<?= $Column->getRowid() ?>">
                        <div class="column-title text-center">
                            <div class="row" style="height : 85px">
                                <div class="col-7 pt-3 ps-2 ms-3 pe-0 column-title-name">
                                    <div class="overflow-x">
                                        <b class="column-title-text"><?= $Column->getName() ?></b>
                                    </div>
                                </div>
                                <ul class="offset-1 col-3 pt-2 ps-0">
                                    <li class="me-2"><button class="btn btn-outline-dark add-task-btn">New</button></li>
                                    <?php if($Column->getName() != "Open" && $Column->getName() != "Closed"){ ?>
                                        <li class="mt-2 me-2"><button class="btn btn-outline-danger delete-col-btn">Delete</button></li>
                                    <?php } ?>
                                </ul>
                            </div>
                        </div>
                        <div class="column-content">
                            <?php foreach($Column->getActiveTasks() as $taskKey => $Task) {
                                $isAdmin = false;
                                foreach($TeamUsers as $TeamUser) {
                                    if($Task->getFk_user() == $TeamUser->getRowid() && $TeamUser->isAdmin()) {
                                        $isAdmin = true;
                                        break;
                                    }
                                } ?>
                                <div class="task">
                                    <input class="taskId-input" type="hidden" value="<?= $Task->getRowid() ?>">
                                    <button class='btn disabled <?= $isAdmin ? 'btn-outline-danger w-75' : 'btn-outline-classic w-50' ?> line-height-40 mt-2 ms-2 px-0 overflow-x'><?= $authors[$columnKey][$taskKey] ?></button>
                                    <div class='task-bubble pt-2 mb-1 mt-1 mx-2'>
                                        <textarea class='task-bubble-input text-center pt-1'><?= $Task->getName() ?></textarea>
                                    </div>
                                    <div class="d-flex justify-content-between pe-2 ps-2">
                                        <div class="collapse mx-auto task-buttons-container">
                                            <i class="bi bi-check-lg btn btn-outline-success task-check" tabindex="0" data-bs-toggle="tooltip" title="Enregistrer"></i>
                                            <i class="bi bi-trash ms-1 btn btn-outline-danger task-delete"></i>
                                            <i class="bi bi-caret-left-fill ms-1 btn btn-outline-dark arrow-img-btn task-to-left"></i>
                                            <i class="bi bi-caret-right-fill ms-1 btn btn-outline-dark arrow-img-btn task-to-right"></i>
                                            <i class="bi bi-archive-fill task-archive ms-1 me-1 btn btn-outline-danger"></i>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>   
                <?php } ?>
            </div>
        </div>
    </div>

    <div id="details-section" class="col-sm-4 col-md-3 col-lg-2 pt-1 pe-4 text-center border position-relative collapse show" style="height: 100vh">
        <div class="row justify-content-center">
            <div class="col-5">
                <?php if($Team->isActive()) { ?>
                    <i id="archive-btn" class="bi bi-archive-fill btn btn-outline-danger w-100 mb-2 collapse show" tabindex="0" data-bs-toggle="tooltip" title="Archiver le tableau" data-bs-placement="left"></i>
                <?php } else { ?>
                    <a href="<?= CONTROLLERS_URL ?>admin/map.php?action=openTeam&projectId=<?= $Project->getRowid() ?>&teamId=<?= $teamId ?>"><i id="unarchive-btn" class="bi bi-archive-fill btn btn-outline-success w-75 mb-2 collapse show" tabindex="0" data-bs-toggle="tooltip" title="Désarchiver le tableau" data-bs-placement="left"></i></a>
                <?php } ?>
            </div>
            <div class="col-5">
                <i id="show-archive-tasks-modal" class="bi bi-list-task btn btn-outline-success w-100"></i>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <button id="add-column-btn" class="btn btn-outline-dark collapse show">Nouvelle colonne</button>
            </div>
        </div>
        <div id="task-details" class="mt-3 collapse">
            <div class="mt-3">
                <i id="up-task-btn" class="w-25 me-2 bi bi-arrow-up btn btn-outline-dark"></i>
                <i id="down-task-btn" class="w-25 ms-2 bi bi-arrow-down btn btn-outline-dark"></i>
            </div>
            <div class="border ps-2 pb-4 mt-3 radius" style="height: 32vh;">
                <div id="task-comment-container" class="overflow-y pe-2 pb-3" style="height: 80%"></div>
                <i id="add-comment-btn" class="bi bi-chat-square-text-fill btn btn-outline-classic mt-3 me-2 collapse show w-25" style="font-size: larger;"></i>
                <i id="check-comment-btn" class="btn btn-outline-success mt-3 me-2 collapse bi bi-check-lg" tabindex="0" data-bs-toggle="tooltip" title="Enregistrer"></i>
                <i id="delete-comment-btn" class="mt-3 me-2 btn btn-outline-danger collapse bi bi-trash" tabindex="0" data-bs-toggle="tooltip" title="Supprimer"></i>
            </div>
            
            <div id="members-container-div">
                <div class="row mt-2">
                    <div class="col-4 pt-3 ps-1">
                        <button id="members-switch-button" class="btn btn-outline-info ms-2 p-1 mb-1" style="line-height: 70%;float: left">
                            <div class="row mx-auto">
                                <div class="col-6 p-0">
                                    <i class="bi bi-caret-left-fill"></i>
                                </div>
                                <div class="col-6 p-0">
                                    <i class="bi bi-caret-right-fill"></i>
                                </div>
                            </div>
                        </button>
                    </div>
                    <div class="col-8 pt-3 text-start">
                        <h5 class="members-label collapse">Team members</h5>
                        <h5 class="members-label collapse show">Task members</h5>
                    </div>
                </div>
                <div id="team-members-container" class="overflow-y border collapse pt-1 pb-3 radius" style="height: 25vh;"></div>
                <div id="task-members-container" class="overflow-y border collapse show pt-1 pb-3 radius" style="height: 25vh; width:100%;"></div>

                <button id="attributed-member-button" class="btn btn-outline-classic collapse w-50 mt-2" disabled>Attribué</button>
                <button id="attribute-member-button" class="collapse btn btn-outline-success w-50 mt-2">Attribuer</button>
                <button id="desattribute-member-button" class="collapse btn btn-outline-danger mt-2">Désattribuer</button>
                
                <button id="finish-task-button" class="btn btn-warning w-100 mt-3 collapse">Terminer la tâche</button>
            </div>
        </div>
        <div id="add-column-form" class="sticker text-center pt-1 collapse w-100" style="height:91%">
            <h4 class="border-bottom w-75 mx-auto">Nouvelle colonne</h4>
            <div class="mt-5">
                <label for="columnName-input">Titre</label>
                <input id="columnName-input" class="form-control w-75 mx-auto text-center" type="text">
                <button id="create-column" class="btn btn-outline-success w-75 mt-5">Créer</button>
                <button id="cancel-column" class="btn btn-outline-danger w-75 mt-3">Annuler</button>
            </div>
        </div>
        <div id="column-details" class="mt-3 collapse">
            <textarea id="column-title" class="card px-2 pt-3 text-center" cols="25" rows="2"></textarea>
            <i id="column-details-check-btn" class="bi bi-check-lg btn btn-outline-success w-25 mt-3 invisible p-0" style="font-size: 1.5rem;"></i>
            <div class="mt-5 mx-auto row justify-content-center">
                <div class="col-5">
                    <i id="left-column-btn" class="w-100 bi bi-arrow-left btn btn-outline-dark"></i>
                </div>
                <div class="col-5">
                    <i id="right-column-btn" class="w-100 bi bi-arrow-right btn btn-outline-dark"></i>
                </div>
            </div>
            <i id="column-details-delete-btn" class="bi bi-trash-fill btn btn-outline-danger w-75 mt-4" tabindex="0" data-bs-toggle="tooltip" title="Supprimer la colonne"></i>
        </div>
    </div>

<script type="text/Javascript" src="<?= JS_URL ?>admin/map.min.js" defer></script>
<?php 
require_once "layouts/footer.php";
?>