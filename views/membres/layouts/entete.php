<?php
require_once "../../traitements/header.php";
$rights = $_SESSION["habilitation"] ?? false;

if($rights == 'admin')
{
    header("location:".ROOT_URL."index.php");
}

if($rights == "user")
{
?>
 
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">

        <title>Zi Project</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
        <link rel="stylesheet" href="<?= ROOT_URL ?>style.css">

    </head>
    <body style="overflow:hidden">
    <nav class="navbar navbar-dark navbar-expand-md bg-dark">

        <a class="navbar-brand" href="<?= ROOT_URL ?>index.php">
            <img src="<?= IMG_URL ?>logo.png" width="30" height="30" class="d-inline-block align-top" alt="">
            Projet gestion projets
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <div class="navbar-nav w-100" style="padding-right: 5vh;">

                <div style="margin-left:auto">
                    <a class="btn btn-danger" href="<?= CONTROLLERS_URL ?>general/Deconnexion.php" >Déconnexion</a>
                </div>

            </div>
        </div>

    </nav>

    <!-- <div class="container mt-4" style="width: 50%"> -->

    <!-- <div class="container mt-4"> -->

        <!-- SIDE BAR -->
    <div class="row" style="height: 100%;">
        <div class="col-2">
        </div>

        <div class="text-center pt-2 bg-light col-2" style="height: 95vh; border: 1px solid rgba(0, 0, 0, 0.125); position: fixed">
            <nav>
                <ul class="list-unstyled">
                    <li><a href="<?= VIEWS_URL ?>admin/index.php" class="btn btn-outline-secondary w-75">Accueil</a></li>


                    <!-- PROJETS -->
                    <li class="mt-2">
                        <button class="btn btn-outline-primary w-75 mt-4" data-bs-toggle="collapse" data-bs-target="#projetsCollapse" aria-expanded="false">Projets</button>

                        <div id="projetsCollapse" class="collapse">
                            <ul class="list-unstyled">
                                <li><a class="btn btn-outline-primary w-75 mt-2" href="<?= VIEWS_URL ?>membres/projets.php">Vue d'ensemble</a></li>
                                <li><a class="btn btn-outline-secondary w-75 mt-2" href="<?= CONTROLLERS_URL ?>membres/creationProjets.php">Création de projets</a></li>
                                <li><a class="btn btn-outline-secondary w-75 mt-2" href="<?= CONTROLLERS_URL ?>membres/listeProjets.php">Liste des projets</a></li>
                            </ul>
                        </div>
                    
                    </li>

                    <!-- PROFIL -->
                    <li class="mt-2">
                        <a href="<?= CONTROLLERS_URL ?>membres/profil.php" class="btn btn-outline-primary w-75 mt-4" >Profil</a>
                    </li>
                </ul>
            </nav>

        </div>
    <?php
} 
else 
{
    header("location:".ROOT_URL."index.php");
}
?>
