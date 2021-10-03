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


<div class="row ms-3 me-4 overflow-x" style="height: 90%;">
    <?php
    foreach($CurrentTeam->getMapColumns() as $column)
    {
    ?>
        <div class="project-column">
            <div class="column-title text-center pt-2">
                <b><?= $column->getName() ?></b>
                <button class="btn btn-outline-dark add-task-btn me-3">New</button>
            </div>
            <div class="column-content">
                
            </div>
        </div>
            
    <?php } ?>

    <div class="col" style="width: 250px;">
        <button id="add-column-btn" class="btn btn-outline-dark" style="width:max-content; height:min-content; line-height:80%">Add Column</button>
       
        <div id="add-column-form" class="sticker text-center mt-2 py-2 collapse" style="height: max-content;">
            <input class="form-control w-75 mx-auto text-center" type="text">
            <button class="btn btn-outline-primary w-75 mt-2">Create</button>
        </div>
    </div>
</div>

<script>
    var IMG_URL = <?php echo json_encode(IMG_URL); ?>;
</script>

<script type="text/Javascript" src="<?= JS_URL ?>membres/map.js"></script>
<?php 
require_once "layouts/pied.php";
?>