<?php
require_once "layouts/entete.php";
?>

<div class="col-9 mt-4">
<?php
if($success)
{ ?>
    <div class="alert alert-success">
        <?= $success; ?>
    </div>
    <?php
}
else if ($erreurs)
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

// var_dump($currentProjects);


?>

<h1 class="text-center mx-auto w-50 mt-5 mb-4" style="border-bottom: 1px rgb(216, 214, 214) solid;">Liste des projets</h1>

    <div class="sticker" style="width:80vw; height:65vh; overflow:auto">
        <?php
if($currentProjects)
{ ?>
    <div class="row w-75 mx-auto mt-3 justify-content-between">
        <div class="col-3 sticker-blue text-center"><h5 class="mt-3">Nom du projet</h5></div>
        <div class="col-2 sticker-blue text-center"><h5 class="mt-3">Type</h5></div>
        <div class="col-2 sticker-blue text-center"><h5 class="mt-3">Tâches à faire</h5></div>
        <div class="col-2 sticker-blue text-center"><h5 class="mt-3">Tâches en cours</h5></div>
        <div class="col-2 sticker-blue text-center"><h5 class="mt-3">Détails</h5></div>
    </div>
    <?php
    foreach($currentProjects as $project)
    { ?>
    <div class="row sticker w-75 mx-auto mt-4" style="height: 80px;">
        <div class="col-3 text-center pt-4 mx-auto"><b><?= $project->name ?></b></div>
        <div class="col-2 text-center pt-4 mx-auto"><b><?= $project->type ?></b></div>
        <div class="col-2 text-center pt-4 mx-auto"><b><?= $project->todoCounter ?></b></div>
        <div class="col-2 text-center pt-4 mx-auto"><b><?= $project->progressCounter ?></b></div>
        <div class="col-2 text-center pt-3 mx-auto">
            <a href="<?= CONTROLLERS_URL ?>admin/detailsProjet.php?idProject=<?= $project->rowid ?>" class="btn btn-info mt-1">Détails</a>
        </div>
    </div>
    <?php
    }   
}
else
{ ?>
        <div class="sticker w-75 mx-auto text-center mt-4">
            <h3 class="mt-2">Votre organisation n'a aucun projet en cours.</h3>
        </div>
        <?php 
}
?>

    </div>

<?php
require_once "layouts/pied.php";
?>