<?php
require_once "../traitements/header.php";
// vérification connecté + admin
if(!empty($_SESSION["habilitation"]) && $_SESSION["habilitation"] == "admin")
{
    ?>
    <!DOCTYPE html>
    <html lang="fr">
    <head>
        <meta charset="UTF-8">

        <title>Zi Project</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
        <link rel="stylesheet" href="../style.css">

    </head>
    <body style="overflow-x: hidden;">
    <nav class="navbar navbar-dark navbar-expand-md bg-dark">
        <a class="navbar-brand" href="../index.php">
            <img src="../images/logo.png" width="30" height="30" class="d-inline-block align-top" alt="">
            Projet gestion projets
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <div class="navbar-nav w-100" style="padding-right: 5vh;">
                
                <div style="margin-left:auto">
                    <a class="btn btn-danger" href="../traitements/deconnexion.php">Déconnexion</a>
                </div>

            </div>
        </div>

    </nav>
    <div class="row">
        <div class="col-2 text-center pt-2 bg-light" style="height: 95vh; border: 1px solid rgba(0, 0, 0, 0.125)">
            <nav>
                <ul class="list-unstyled">
                    <li><a href="index.php" class="btn btn-outline-secondary w-75">Accueil</a></li>
                    <li>
                        <button class="btn btn-outline-primary w-75 mt-4" data-bs-toggle="collapse" data-bs-target="#equipesCollapse" aria-expanded="false">Utilisateurs</button>

                        <div id="equipesCollapse" class="collapse">
                            <ul class="list-unstyled">
                                <li><a class="btn btn-outline-primary w-75 mt-2" href="equipesAdmin.php">Vue d'ensemble</a></li>
                                <li><a class="btn btn-outline-secondary w-75 mt-2" href="inscriptionUtilisateur.php">Inscriptions</a></li>
                                <li><a class="btn btn-outline-secondary w-75 mt-2" href="listeMembres.php">Liste des membres</a></li>
                                <li><a class="btn btn-outline-secondary w-75 mt-2" href="gererEntreprise.php">Postes et Équipes</a></li>
                            </ul>
                        </div>
                    </li>
                    <li class="mt-2">
                        <button class="btn btn-outline-primary w-75 mt-4" data-bs-toggle="collapse" data-bs-target="#organisationCollapse" aria-expanded="false">Organisation</button>

                        <div id="organisationCollapse" class="collapse">
                            <ul class="list-unstyled">
                                <li><a class="btn btn-outline-primary w-75 mt-2" href="gestionOrganisation.php">Vue d'ensemble</a></li>
                                <li><a class="btn btn-outline-secondary w-75 mt-2" href="#">Option 1</a></li>
                            </ul>
                        </div>
                    
                    </li>
                </ul>
            </nav>

        </div>

    <?php
} else {
    header("location:../index.php");
}
// print_r($_SESSION);
?>
