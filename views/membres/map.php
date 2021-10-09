<?php 
require_once "layouts/entete.php";
?>
<div class="row" style="height: 100%;">
    <div class="col-10 mt-3 ps-3" style="height: 100%;">
    <?php

    if($success)
    { ?>
        <div class="alert alert-success mx-3">
            <?= $success ?>
        </div>
        <?php 
    }
    else if ($errors)
    { ?>
        <div class="alert alert-danger mx-3">
            <?php 
            foreach($errors as $error)
            {
                echo $error . "<br>";
            }
            ?>
        </div>
    <?php
    } ?>

        <div id="#success" class="alert alert-success mx-3 collapse"></div>
        <div id="#error" class="alert alert-danger mx-3 collapse"></div>

        <div class="row ms-3 me-4 overflow-x" style="height: 90%;">
            <?php
            foreach($CurrentTeam->getMapColumns() as $column)
            {
            ?>
                <div class="project-column">
                    <input class="columnId-input" type="hidden" value="<?= $column->getRowid() ?>">
                    <div class="column-title text-center pt-2">
                        <ul>
                            <li class="me-2"><b><?= $column->getName() ?></b><button class="btn btn-outline-dark add-task-btn">New</button></li>
                            <li class="mt-2 me-2"><button class="btn btn-outline-danger delete-col-btn">Delete</button></li>
                        </ul>
                    </div>
                    <div class="column-content">
                        <?php 
                        foreach($column->getTasks() as $task)
                        {   
                            ?>
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
                            <?php
                        }
                        ?>
                    </div>
                </div>   
            <?php } ?>
                
            <div class="col" style="width: 250px;">
                <button id="add-column-btn" class="btn btn-outline-dark" style="width:max-content; height:min-content; line-height:80%">Add Column</button>
            
                <div id="add-column-form" class="sticker text-center mt-2 py-2 collapse" style="height: max-content; width: 10vw">
                    <input id="columnName-input" class="form-control w-75 mx-auto text-center" type="text">
                    <button class="btn btn-outline-primary w-75 mt-2">Create</button>
                </div>
            </div>
        </div>
    </div>

    <div class="col-2 pt-4 pe-4 text-center border" style="height: 100vh">
        <div id="task-details" class="collapse">
            <h1>Title</h1>
            <button id="up-task-btn" class="btn btn-outline-dark w-75">Up task</button>
            <button id="down-task-btn" class="btn btn-outline-dark w-75 mt-3">Down task</button>
            <h5 class="mt-3">Comment flow</h5>
            <div class="border pb-3 ps-2" style="height: 33vh;">
                <div id="task-comment-container" class="overflow-y pe-2" style="height: 80%">
                    <!-- foreach description -->
                    <!-- <textarea class="mt-3 card task-comment px-2 text-center" name="" id="" cols="30" rows="3" style="width: 100%; background-color: #f8f9fa"></textarea> -->
                </div>
                <button id="add-comment-btn" class="btn btn-outline-dark mt-3 me-2 collapse show">Add comment</button>
                <button id="check-comment-btn" class="btn btn-outline-dark mt-3 me-2 collapse">Check</button>
            </div>
            
            <h5 class="mt-3">Team members</h5>
            <div>
                
                </div>
                <div class="overflow-y border" style="height: 20vh;">
                    <div class="sticker mx-auto mt-2 hover text-center pt-3" style="width: 90%;">NOM DU MEMBRE</div>
                </div>
                <button class="btn btn-outline-secondary w-50 mt-2">Attribute</button>
                <button class="btn btn-outline-secondary w-50 mt-2">Desattribute</button>
            </div>
        </div>
    </div>

<script>
    const IMG_URL = <?php echo json_encode(IMG_URL); ?>;
    const CONTROLLERS_URL = <?php echo json_encode(CONTROLLERS_URL); ?>;
    const AJAX_URL = <?php echo json_encode(AJAX_URL); ?>;
    var projectId = <?php echo json_encode($CurrentProject->getId()); ?>;
    var teamId = <?php echo json_encode($CurrentTeam->getId()); ?>;
</script>

<script type="text/Javascript" src="<?= JS_URL ?>membres/map.js"></script>
<?php 
require_once "layouts/pied.php";
?>