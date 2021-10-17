<?php 
require_once "layouts/entete.php";
?>
<div class="row position-relative" style="height: 100%;">

    <?php if ($errors) { ?>
        <div class="alert alert-danger w-50 text-center position-absolute top-0 start-50 translate-middle-x">
            <?php foreach($errors as $error) { ?>
                <i class="bi bi-exclamation-triangle-fill"></i>
                <?php echo $error . "<br>";
            } ?>
        </div>
    <?php } ?>

    <a href="<?= CONTROLLERS_URL ?>admin/detailsProjet.php?idProject=<?= $CurrentProject->getId() ?>" ><i class="btn btn-outline-dark bi bi-box-arrow-left position-absolute start-0 top-0 mt-2 me-2 w-auto"></i></a>
    <i id="open-right-section" class="btn btn-outline-dark bi bi-arrow-bar-left position-absolute end-0 top-0 mt-2 me-2 w-auto collapse"></i>

    <div id="archive-confirmation" class="collapse mt-3">
        <h3 class="mx-auto text-center border-bottom w-50">Archiver le projet</h3>

        <div class="sticker h-auto w-50 mx-auto text-center mt-3 pb-5">
            <p class="mt-5"><b>Êtes-vous sûr de vouloir archiver le projet ? (vous pourrez le ré-ouvrir plus tard)</b></p>
            <a href="<?= CONTROLLERS_URL ?>admin/map.php?action=archive&projectId=<?= $CurrentProject->getId() ?>" class="btn btn-outline-success w-50 mt-5">Archiver le projet</a>
            <a id="cancel-archive" class="btn btn-outline-danger w-50 mt-3">Annuler</a>
        </div>
    </div>

    <div id="left-section" class="col-10 mt-3 ps-3" style="height: 100%;">
        <div class="collapse show">
            <div id="columns-container" class="ms-3 me-4 overflow-x d-flex" style="height: 88vh;">
                <?php foreach($CurrentTeam->getMapColumns() as $column) { ?>
                    <div class="project-column">
                        <input class="columnId-input" type="hidden" value="<?= $column->getRowid() ?>">
                        <div class="column-title text-center">
                            <div class="row">
                                <div class="col-7 pt-3 ps-2 ms-3 pe-0 column-title-name">
                                    <div class="overflow-x">
                                        <b class="column-title-text"><?= $column->getName() ?></b>
                                    </div>
                                </div>
                                <ul class="offset-1 col-3 pt-2 ps-0">
                                    <li class="me-2"><button class="btn btn-outline-dark add-task-btn">New</button></li>
                                    <li class="mt-2 me-2"><button class="btn btn-outline-danger delete-col-btn">Delete</button></li>
                                </ul>
                            </div>
                        </div>
                        <div class="column-content">
                            <?php foreach($column->getTasks() as $task) { ?>
                                <div class="task">
                                    <input class="taskId-input" type="hidden" value="<?= $task->getRowid() ?>">
                                    <div class='task-bubble mt-2 pt-3 mb-1 mx-2'>
                                        <textarea class='task-bubble-input text-center'><?= $task->getName() ?></textarea>
                                    </div>
                                    <a class='ms-2 btn btn-outline-success task-check collapse'>Check</a>
                                    <a class='ms-1 btn btn-outline-danger task-delete collapse'>Delete</a>
                                    <a class="ms-1 btn btn-outline-dark arrow-img-btn task-to-left collapse">
                                        <img src="<?= IMG_URL ?>left.png" alt="" width="30px">
                                    </a>
                                    <a class="ms-1 btn btn-outline-dark arrow-img-btn task-to-right collapse">
                                        <img src="<?= IMG_URL ?>right.png" alt="" width="30px">
                                    </a>
                                </div>
                            <?php } ?>
                        </div>
                    </div>   
                <?php } ?>
            </div>
        </div>
    </div>

    <div id="details-section" class="col-2 pt-1 pe-4 text-center border position-relative collapse show" style="height: 100vh"> 
        <!-- <button id="archive-btn" class="btn btn-danger w-75 mb-2" style="line-height: 80%;">Archiver le Projet</button> -->
        <div class="row">
            <div class="col">
                <i id="archive-btn" class="bi bi-archive-fill btn btn-outline-danger w-75 mb-2 collapse show"></i>
            </div>
            <div class="col">
                <button id="add-column-btn" class="btn btn-outline-dark collapse show" style="width:max-content; height:min-content; line-height:80%">Add Column</button>
            </div>
            <div class="col">
                <button id="close-details" type="button" class="btn-close position-absolute top-0 end-0 me-4 mt-2" aria-label="Close"></button>
            </div>
        </div>
        <div id="task-details" class="mt-3 collapse">
            <textarea id="task-title" class="card px-2 pt-3 text-center" cols="25" rows="2" readonly>Title</textarea>
            <div class="mt-2">
                <i id="up-task-btn" class="w-25 me-2 bi bi-arrow-up btn btn-outline-dark"></i>
                <i id="down-task-btn" class="w-25 ms-2 bi bi-arrow-down btn btn-outline-dark"></i>
            </div>
            <div class="border ps-2 pb-4 mt-2" style="height: 28vh;">
                <div id="task-comment-container" class="overflow-y pe-2" style="height: 80%"></div>
                <button id="add-comment-btn" class="btn btn-outline-dark mt-3 me-2 collapse show">Commenter</button>
                <i id="check-comment-btn" class="btn btn-outline-dark mt-3 me-2 collapse bi bi-check-lg"></i>
                <i id="delete-comment-btn" class="mt-3 me-2 btn btn-outline-dark collapse bi bi-trash"></i>
            </div>
            
            <div id="members-container-div">
                <div class="row mt-2">
                    <div class="col-4 pt-3">
                        <button id="members-switch-button" class="btn btn-outline-info" style="line-height: 70%;">switch</button>
                    </div>
                    <div class="col-8 pt-3 text-start">
                        <h5 class="members-label collapse">Team members</h5>
                        <h5 class="members-label collapse show">Task members</h5>
                    </div>
                </div>
                <div id="team-members-container" class="overflow-y border collapse" style="height: 20vh;">
                    <?php
                    foreach($CurrentTeam->getMembers() as $member) { ?>
                    <div class="team-member">
                        <input type="hidden" class="team-member-id" value="<?= $member->getId() ?>">
                        <div class="sticker mx-auto mt-2 hover text-center pt-3" style="width: 90%;"><?= $member->getLastname() . " " . $member->getFirstname() ?></div>
                    </div>
                    <?php } ?>
                </div>
                <div id="task-members-container" class="overflow-y border collapse show" style="height: 20vh; width:100%">
                    
                </div>

                <button id="attributed-member-button" class="btn btn-outline-classic collapse w-50 mt-2" disabled>Attribué</button>
                <button id="attribute-member-button" class="collapse btn btn-outline-success w-50 mt-2">Attribuer</button>
                <button id="desattribute-member-button" class="collapse btn btn-outline-danger w-50 mt-2">Désattribuer</button>
            </div>
        </div>
        <div id="add-column-form" class="sticker text-center pt-1 collapse w-100" style="height:91%">
            <h3 class="border-bottom w-75 mx-auto">New Column</h3>
            <div class="mt-5">
                <label for="columnName-input">Column Name</label>
                <input id="columnName-input" class="form-control w-75 mx-auto text-center" type="text">
                <button id="create-column" class="btn btn-outline-success w-75 mt-5">Create</button>
                <button id="cancel-column" class="btn btn-outline-danger w-75 mt-3">Cancel</button>
            </div>
        </div>
        <div id="column-details" class="mt-3 collapse">
            <textarea id="column-title" class="card px-2 pt-3 text-center" cols="25" rows="2"></textarea>
            <button id="column-details-check-btn" class="btn btn-outline-dark w-50 mt-3 collapse">Check</button>
            <div class="mt-5 row justify-content-around">
                <!-- <button id="left-column-btn" class="btn btn-outline-dark" style="width: 40%;">Left</button>
                <button id="right-column-btn" class="btn btn-outline-dark" style="width: 40%;">Right</button> -->
                <div class="col">
                    <i id="left-column-btn" class="w-100 bi bi-arrow-left btn btn-outline-dark"></i>
                </div>
                <div class="col">
                    <i id="right-column-btn" class="w-100 bi bi-arrow-right btn btn-outline-dark"></i>
                </div>
            </div>
            <button id="column-details-delete-btn" class="btn btn-outline-danger w-75 mt-4">Delete</button>
        </div>
    </div>

<script type="text/Javascript" src="<?= JS_URL ?>admin/map.js"></script>
<?php 
require_once "layouts/pied.php";
?>