<?php
require_once 'layouts/entete.php';
?>

<div class="col-10">

<?php
if($erreurs)
{ ?>
    <div class="alert alert-danger">

        <?php
    foreach($erreurs as $erreur)
    {
        echo $erreur . "<br>";
    } ?>
    </div>
    <?php 
}
else if ($success)
{ ?>
    <div class="alert alert-success">
    <?php
    echo $success;
    ?>
    </div>
    <?php
}


?>
    <div class="row">

        <div class="sticker col-3 mt-3 ms-3 me-3 text-center overflow-x" style="height: 60px; ">
            <h3 class="mt-2"><?= $CurrentProject->name; ?></h3>
        </div>
        
        <div class="sticker col mt-3 me-4 text-center">
            <h3 class="text-center mt-2">Equipes</h3>
        </div>

    </div>

    <div class="row">
        <div class="sticker col-3 mt-3 ms-3 me-3 text-center" style="height: 75vh;">
            <form action="" method="POST">
                <h5 class="mt-5">Titre</h5>
                <input class="sticker text-center mt-2" name="name" id="name" type="text" value="<?= $CurrentProject->name ?>">

                <h5 class="mt-3">Description</h5>
                <input class="sticker text-center mt-2" name="description" id="description" type="text" value="<?= $CurrentProject->description ?>">

                <h5 class="mt-3">Type</h5>
                <input class="sticker text-center mt-2" name="type" id="type" type="text" value="<?= $CurrentProject->type ?>">


            </form>
        </div>

        <div class="sticker col mt-3 me-4 text-center" style="height: 75vh;">

        </div>
    </div>




<?php 

require_once 'layouts/pied.php';

?>