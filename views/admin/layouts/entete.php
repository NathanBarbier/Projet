<?php
// require_once "../../traitements/header.php";

$rights = $_SESSION["rights"] ?? false;

if($rights !== "admin")
{
    header("location:".ROOT_URL."index.php");
} ?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">

    <title>Stories Helper</title>

    <link rel="icon" href="<?= IMG_URL ?>logo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="<?= ROOT_URL ?>style.css">
    <script src="<?= ROOT_URL ?>bower_components/jquery/dist/jquery.min.js"></script>
    
</head>

<body style="overflow-x: hidden;">
    <nav class="navbar navbar-dark navbar-expand-md bg-dark w-100" style="height: 60px; position: fixed; z-index : 999">
        <a class="navbar-brand mb-1" href="<?= ROOT_URL ?>index.php">
            <img src="<?= IMG_URL ?>logo.png" width="35" height="35" class="d-inline-block align-top ms-3 me-2" alt="">
            Stories Helper
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <div class="navbar-nav w-100" style="padding-right: 5vh;">
                
                <div style="margin-left:auto">
                    <a class="btn btn-danger" href="<?= CONTROLLERS_URL ?>general/Deconnexion.php">Déconnexion</a>
                </div>

            </div>
        </div>

    </nav>
    <div class="row" style="height: 60px;">
    </div>
    <?php if($pageName != 'map.php') { ?>
    <!-- SIDE BAR -->
    <div class="row">
        <div class="col-2">
        </div>
        <div class="text-center pt-2 bg-light col-2" style="height: 95vh; border: 1px solid rgba(0, 0, 0, 0.125); position: fixed">
            <nav>
                <ul class="list-unstyled">
                    <li><a href="<?= VIEWS_URL ?>admin/index.php" class="btn btn-outline-secondary w-75">Accueil</a></li>
                    <!-- COLLABORATEURS -->
                    <li>
                        <button class="btn btn-outline-primary w-75 mt-4" data-bs-toggle="collapse" data-bs-target="#equipesCollapse" aria-expanded="false">Collaborateurs</button>

                        <div id="equipesCollapse" class="collapse">
                            <ul class="list-unstyled">
                                <li><a class="btn btn-outline-primary w-75 mt-2" href="<?=CONTROLLERS_URL ?>admin/collaborateurs.php">Vue d'ensemble</a></li>
                                <li><a class="btn btn-outline-secondary w-75 mt-2" href="<?= CONTROLLERS_URL ?>admin/inscriptionUtilisateur.php">Inscriptions</a></li>
                                <li><a class="btn btn-outline-secondary w-75 mt-2" href="<?= CONTROLLERS_URL ?>admin/listeMembres.php">Liste des membres</a></li>
                                <li><a class="btn btn-outline-secondary w-75 mt-2" href="<?= CONTROLLERS_URL ?>admin/postesEquipes.php">Postes et Équipes</a></li>
                            </ul>
                        </div>
                    </li>
                    <!-- ORGANISATION -->
                    <li class="mt-2">
                        <button class="btn btn-outline-primary w-75 mt-4" data-bs-toggle="collapse" data-bs-target="#organisationCollapse" aria-expanded="false">Organisation</button>

                        <div id="organisationCollapse" class="collapse">
                            <ul class="list-unstyled">
                                <li><a class="btn btn-outline-primary w-75 mt-2" href="<?= CONTROLLERS_URL ?>admin/organisation.php">Vue d'ensemble</a></li>
                                <li><a class="btn btn-outline-secondary w-75 mt-2" href="<?= CONTROLLERS_URL ?>admin/gestionOrganisation.php">Gestion organisation</a></li>
                            </ul>
                        </div>
                    
                    </li>
                    <!-- PROJETS -->
                    <li class="mt-2">
                        <button class="btn btn-outline-primary w-75 mt-4" data-bs-toggle="collapse" data-bs-target="#projetsCollapse" aria-expanded="false">Projets</button>

                        <div id="projetsCollapse" class="collapse">
                            <ul class="list-unstyled">
                                <li><a class="btn btn-outline-primary w-75 mt-2" href="<?= CONTROLLERS_URL ?>admin/projets.php">Vue d'ensemble</a></li>
                                <li><a class="btn btn-outline-secondary w-75 mt-2" href="<?= CONTROLLERS_URL ?>admin/creationProjets.php">Création de projets</a></li>
                                <li><a class="btn btn-outline-secondary w-75 mt-2" href="<?= CONTROLLERS_URL ?>admin/listeProjets.php">Liste des projets</a></li>
                            </ul>
                        </div>
                    
                    </li>
                </ul>
            </nav>

        </div>
        <?php } ?>
    