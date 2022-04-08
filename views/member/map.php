<?php 
require_once "layouts/header.php";
?>
<div class="row position-relative bg-white" style="height: 100%;">

    <!-- Back Page -->
    <a href="<?= CONTROLLERS_URL ?>member/dashboard.php" ><i class="btn btn-outline-dark bi bi-box-arrow-left position-absolute start-0 top-0 mt-2 me-2 w-auto before"></i></a>
    <!-- Expand right section -->
    <i id="open-right-section" class="btn btn-outline-dark bi bi-arrow-bar-left position-absolute end-0 top-0 mt-2 me-2 w-auto collapse"></i>

    <div id="left-section" class="col-sm-8 col-md-9 col-lg-10 mt-2 ps-3">
        <div class="collapse show position-relative">
            <i id="close-details" class="btn btn-outline-dark bi bi-arrow-bar-right position-absolute end-0 top-0 w-auto collapse show"></i>
            <div id="columns-container" class="ms-3 overflow-x d-flex">
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
                            <?php 
                            foreach($Column->getActiveTasks() as $taskKey => $Task) 
                            {
                                $isAdmin = false;
                                foreach($TeamUsers as $TeamUser) 
                                {
                                    if($Task->getFk_user() == $TeamUser->getRowid() && $TeamUser->isAdmin()) 
                                    {
                                        $isAdmin = true;
                                        break;
                                    }
                                } ?>
                                <div class="task">
                                    <input class="taskId-input" type="hidden" value="<?= $Task->getRowid() ?>">
                                    <button class='btn disabled <?= $isAdmin ? 'btn-outline-danger' : 'btn-outline-classic' ?> line-height-40 mt-2 ms-2 px-0 overflow-x' style='width: 65%'>
                                    <?= !empty($authors[$columnKey][$taskKey]) ? $authors[$columnKey][$taskKey] : 'undefined' ?>
                                    </button>
                                    <?php 
                                    foreach($Task->getMembers() as $member) 
                                    {
                                        if($member->getRowid() == $CurrentUser->getRowid()) 
                                        { ?>
                                            <button class="btn disabled btn-outline-primary line-height-40 mt-2 p-0 affected-badge">
                                                <i class="bi bi-person-check-fill"></i>
                                            </button>    
                                            <?php 
                                            break;
                                        }
                                    } ?>
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
                            <?php 
                            } ?>
                        </div>
                    </div>   
                <?php } ?>
            </div>
        </div>
    </div>

    <div id="details-section" class="col-sm-4 col-md-3 col-lg-2 pt-1 pe-4 text-center border position-relative collapse show">
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
                <div id="task-comment-container" class="overflow-y pe-2 pb-3"></div>
                <i id="add-comment-btn" class="bi bi-chat-square-text-fill btn btn-outline-classic mt-3 collapse show"></i>
                <i id="check-comment-btn" class="btn btn-outline-success mt-3 me-2 collapse bi bi-check-lg" tabindex="0" data-bs-toggle="tooltip" title="Enregistrer"></i>
                <i id="delete-comment-btn" class="mt-3 me-2 btn btn-outline-danger collapse bi bi-trash" tabindex="0" data-bs-toggle="tooltip" title="Supprimer"></i>
            </div>
            
            <div id="members-container-div">
                <div class="row mt-2">
                    <div class="col-4 pt-3 ps-1">
                        <button id="members-switch-button" class="btn btn-outline-info ms-2 p-1 mb-1 w-75">
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
                    <div class="col-8 pt-3 ps-0">
                        <h6 class="members-label collapse text-center">Membres de l'équipe</h6>
                        <h6 class="members-label collapse show text-center">Affectés à la tâche</h6>
                    </div>
                </div>
                <div id="team-members-container" class="overflow-y border collapse pt-1 pb-3 radius"></div>
                <div id="task-members-container" class="overflow-y border collapse show pt-1 pb-3 radius"></div>

                <button id="attributed-member-button" class="btn btn-outline-classic collapse w-50 mt-2" disabled>Attribué</button>
                <button id="attribute-member-button" class="collapse btn btn-outline-success w-50 mt-2">Attribuer</button>
                <button id="desattribute-member-button" class="collapse btn btn-outline-danger mt-2">Désattribuer</button>
                
                <button id="finish-task-button" class="btn btn-warning w-100 mt-3 collapse">Terminer la tâche</button>
            </div>
        </div>
        <div id="add-column-form" class="sticker text-center pt-1 collapse w-100">
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
            <i id="column-details-check-btn" class="bi bi-check-lg btn btn-outline-success w-25 mt-3 invisible p-0"></i>
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
</div>
<script type="text/Javascript" src="<?= JS_URL ?>member/map.min.js" defer></script>
<?php 
require_once "layouts/footer.php";
?>
