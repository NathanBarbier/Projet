<?php
require_once "layouts/entete.php";

$data = json_decode(GETPOST('data'));
?>
<div class="col-9 mt-4">
<?php
if($data->success)
{ ?>
    <div class="alert alert-success">
        <?= $success; ?>
    </div>
    <?php
}
else if ($data->erreurs)
{ ?>
    <div class="alert alert-danger">
    <?php
    foreach($data->erreurs as $erreur)
    {
        echo $erreur . "<br>";
    } ?>
    </div>
    <?php
}
?>

<h1 class="text-center mx-auto w-50 mt-5 mb-4" style="border-bottom: 1px rgb(216, 214, 214) solid;">Liste des projets</h1>

    <div class="sticker" style="width:80vw; height:65vh; overflow:auto">
        <?php
// if($data->currentProjects)
// {
    // foreach($data->currentProjects as $project)
    // { ?>
    <div class="row sticker w-75 mx-auto mt-4" style="height: 80px;">
        <div class="col-3 text-center pt-4">Nom du projet</div>
        <div class="col-3 text-center pt-4">Type</div>
        <div class="col-3 text-center pt-4">Nombre de tâches</div>
        <div class="col-3 text-center pt-3">
            <button class="btn btn-info">Détails</button>
        </div>
    </div>
    <?php
    // }   
    // }
    // else
    // { ?>
        <!-- <div class="sticker w-75 mx-auto text-center">
            <h3 class="mt-2">Votre organisation n'a aucun projet en cours.</h3>
        </div> -->
        <?php 
// }
?>

    </div>

<?php
require_once "layouts/pied.php";
?>