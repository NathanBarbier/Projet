<?php 
require_once "layouts/entete.php";
?>


<div class="col-10 mt-3" style="height: 100%;">
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
                    // var_dump($task);
                    ?>
                    <input class="taskId-input" type="hidden" value="<?= $task->getRowid() ?>">
                    <div class='task-bubble mt-2 pt-3 mb-1 mx-2'>
                        <textarea class='task-bubble-input text-center'><?= $task->getName() ?></textarea>
                    </div>
                    <a class='ms-2 btn btn-outline-dark task-check collapse'>Check</a>
                    <a class='ms-2 btn btn-outline-danger task-delete collapse'>Delete</a>
                    <?php
                }
                ?>
            </div>
        </div>
            
    <?php } ?>

    <div class="col" style="width: 250px;">
        <button id="add-column-btn" class="btn btn-outline-dark" style="width:max-content; height:min-content; line-height:80%">Add Column</button>
       
        <div id="add-column-form" class="sticker text-center mt-2 py-2 collapse" style="height: max-content;">
            <input id="columnName-input" class="form-control w-75 mx-auto text-center" type="text">
            <button class="btn btn-outline-primary w-75 mt-2">Create</button>
        </div>
    </div>
</div>

<script>
    const IMG_URL = <?php echo json_encode(IMG_URL); ?>;
    const CONTROLLERS_URL = <?php echo json_encode(CONTROLLERS_URL); ?>;
    var projectId = <?php echo json_encode($CurrentProject->getId()); ?>;
</script>

<script type="text/Javascript" src="<?= JS_URL ?>membres/map.js"></script>
<?php 
require_once "layouts/pied.php";
?>