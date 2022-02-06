<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Stories Helper</title>
    <link rel="icon" href="<?= IMG_URL ?>logo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Zilla+Slab:wght@600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="<?= ROOT_URL ?>style.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous" defer></script>
</head>

<body>
    <nav class="navbar navbar-dark bg-dark w-100" style="height: 60px; z-index : 999">
        <a class="navbar-brand mb-1" href="<?= ROOT_URL ?>index.php">
            <img src="<?= IMG_URL ?>logo.png" width="35" height="35" class="d-inline-block align-top ms-3 me-2" alt="">
            Stories Helper
        </a>
        <div class="me-4" style="margin-left:auto">
            <a class="btn btn-outline-danger" href="<?= CONTROLLERS_URL ?>visiteur/deconnexion.php">Déconnexion</a>
        </div>

        <button class="navbar-toggler collapse" type="button" data-bs-toggle="collapse" data-bs-target="#sidebar-content" aria-controls="sidebar-content" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="sidebar-content">
            <div class="navbar-nav w-100" style="background-color: #f8f9fa; border-bottom: 2px solid rgba(0, 0, 0, 0.225)">  
                <nav class="w-100 text-center mt-3">
                    <ul class="list-unstyled">
                        <li><a href="<?= VIEWS_URL ?>admin/index.php" class="btn btn-outline-secondary w-75">Accueil</a></li>
                        <!-- COLLABORATEURS -->
                        <li>
                            <button class="btn btn-outline-primary w-75 mt-4" data-bs-toggle="collapse" data-bs-target="#equipesCollapse" aria-expanded="false">Collaborateurs</button>

                            <div id="equipesCollapse" class="collapse">
                                <ul class="list-unstyled">
                                    <li><a class="btn btn-outline-primary w-75 mt-2" href="<?= CONTROLLERS_URL ?>admin/collaborateurs.php">Vue d'ensemble</a></li>
                                    <li><a class="btn btn-outline-secondary w-75 mt-2" href="<?= CONTROLLERS_URL ?>admin/inscriptionUtilisateur.php">Inscriptions</a></li>
                                    <li><a class="btn btn-outline-secondary w-75 mt-2" href="<?= CONTROLLERS_URL ?>admin/listeMembres.php">Liste des membres</a></li>
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
        </div>
    </nav>
    <!-- <div class="row" style="height: 60px;">
    </div> -->
    <?php if($pageName != 'map.php') { ?>
    <!-- SIDE BAR -->
    <div class="row">
        <div id="sideBar" class="navbar-expand-lg col-md-0 col-lg-2 p-0 collapse show">
            <div class="collapse navbar-collapse align-items-start text-center pt-2 bg-light overflow-y" style="height: 100vh; border: 1px solid rgba(0, 0, 0, 0.125);">
                <nav class="w-100">
                    <ul class="list-unstyled">
                        <li><a href="<?= VIEWS_URL ?>admin/index.php" class="btn btn-outline-secondary w-75">Accueil</a></li>
                        <!-- COLLABORATEURS -->
                        <li>
                            <button class="btn btn-outline-primary w-75 mt-4" data-bs-toggle="collapse" data-bs-target="#equipesCollapse" aria-expanded="false">Collaborateurs</button>

                            <div id="equipesCollapse" class="collapse">
                                <ul class="list-unstyled">
                                    <li><a class="btn btn-outline-primary w-75 mt-2" href="<?= CONTROLLERS_URL ?>admin/collaborateurs.php">Vue d'ensemble</a></li>
                                    <li><a class="btn btn-outline-secondary w-75 mt-2" href="<?= CONTROLLERS_URL ?>admin/inscriptionUtilisateur.php">Inscriptions</a></li>
                                    <li><a class="btn btn-outline-secondary w-75 mt-2" href="<?= CONTROLLERS_URL ?>admin/listeMembres.php">Liste des membres</a></li>
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
        </div>
        <div id="main" class="col-md-12 col-lg-10 pt-3 px-3 position-relative">
            <i id="close-sidebar" class="btn btn-outline-dark bi bi-arrow-bar-left position-absolute start-0 w-auto collapse show before"></i>
            <i id="open-sidebar" class="btn btn-outline-dark bi bi-arrow-bar-right position-absolute start-0 w-auto collapse before"></i>
        <?php } ?>
    