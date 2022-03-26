<?php 
require_once SERVICES_PATH.'notification.php' ;
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Stories Helper</title>
        
        <link rel="icon" href="<?= IMG_URL ?>logo.png">
        <!-- Mozzilla Font -->
        <link href="https://fonts.googleapis.com/css2?family=Zilla+Slab:wght@600&display=swap" rel="stylesheet">
        <!-- Bootstrap cdn -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous" defer>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.0/font/bootstrap-icons.css">
        <!-- Main css sheet -->
        <link rel="stylesheet" href="<?= ASSETS_URL ?>style.css">
        <!-- jQuery -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous" defer></script>
<<<<<<< Updated upstream
        <!-- Toaster cdn -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.min.js" integrity="sha512-lbwH47l/tPXJYG9AcFNoJaTMhGvYWhVM9YI43CT+uteTRRaiLCui8snIgyAN8XWgNjNhCqlAUdzZptso6OCoFQ==" crossorigin="anonymous" referrerpolicy="no-referrer" defer></script>
=======
   <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=G-MSX9XSMFBN"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());

            gtag('config', 'G-MSX9XSMFBN');
        </script>
>>>>>>> Stashed changes
    </head>

    <body class="position-relative">
        <nav id="top-navbar" class="navbar navbar-dark bg-dark w-100 pt-1 before" style="height: 55px;">

            <!-- LOGO -->
            <a class="navbar-brand" href="<?= ROOT_URL ?>index.php">
                <img src="<?= IMG_URL ?>logo.png" width="35" height="35" class="ms-3 mb-2 mt-1 me-2" alt="">
                Stories Helper
            </a>

            <div class="w-50 pb-3" style="float: right;">
                <!-- NAVBAR TOGGLER -->
                <button class="navbar-toggler collapse mt-1 me-2" style="float: right;" type="button" data-bs-toggle="collapse" data-bs-target="#sidebar-content" aria-controls="sidebar-content" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
    
                <!-- DISCONNECT -->
                <a class="btn btn-sm me-2 mt-1 rounded sidebar-btn danger" style="float: right;" href="<?= CONTROLLERS_URL ?>visiteur/deconnexion.php">Déconnexion</a>
            </div>


            <div class="collapse navbar-collapse w-100" id="sidebar-content">
                <div class="navbar-nav w-100" style="background-color: rgba(31,41,55,1); border-bottom: 2px solid rgba(0, 0, 0, 0.225)">  
                    <nav class="w-100 text-center">
                        <ul class="list-unstyled">
                            <li>
                                <a href="<?= CONTROLLERS_URL ?>admin/index.php" class="btn px-3 mt-2 w-90 sidebar-btn">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-house-fill" viewBox="0 0 16 16">
                                        <path fill-rule="evenodd" d="m8 3.293 6 6V13.5a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 2 13.5V9.293l6-6zm5-.793V6l-2-2V2.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5z"/>
                                        <path fill-rule="evenodd" d="M7.293 1.5a1 1 0 0 1 1.414 0l6.647 6.646a.5.5 0 0 1-.708.708L8 2.207 1.354 8.854a.5.5 0 1 1-.708-.708L7.293 1.5z"/>
                                    </svg>
                                    <span class="ms-3">Accueil</span>
                                </a>
                            </li>
                            <!-- COLLABORATEURS -->
                            <li>
                                <button class="btn px-3 mt-2 rounded w-90 mt-2 sidebar-btn" data-bs-toggle="collapse" data-bs-target="#collaborateursCollapse" aria-expanded="false">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-person-circle ms-2" viewBox="0 0 16 16">
                                        <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/>
                                        <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z"/>
                                    </svg>
                                    <span class="ms-3">Collaborateurs</span>
                                </button>

                                <div id="collaborateursCollapse" class="collapse">
                                    <ul class="list-unstyled">
                                        <li><a class="btn px-3 mt-2 rounded w-90 mt-2 sidebar-btn" href="<?= CONTROLLERS_URL ?>admin/collaborateurs.php">Vue d'ensemble</a></li>
                                        <li><a class="btn px-3 mt-2 rounded w-90 mt-2 sidebar-btn" href="<?= CONTROLLERS_URL ?>admin/inscriptionUtilisateur.php">Inscriptions</a></li>
                                        <li><a class="btn px-3 mt-2 rounded w-90 mt-2 sidebar-btn" href="<?= CONTROLLERS_URL ?>admin/listeMembres.php">Liste des membres</a></li>
                                    </ul>
                                </div>
                            </li>
                            <!-- ORGANISATION -->
                            <li class="mt-2">
                                <button class="btn px-3 mt-2 rounded w-90 mt-2 sidebar-btn" data-bs-toggle="collapse" data-bs-target="#organisationCollapse" aria-expanded="false">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-building ms-2" viewBox="0 0 16 16">
                                        <path fill-rule="evenodd" d="M14.763.075A.5.5 0 0 1 15 .5v15a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1-.5-.5V14h-1v1.5a.5.5 0 0 1-.5.5h-9a.5.5 0 0 1-.5-.5V10a.5.5 0 0 1 .342-.474L6 7.64V4.5a.5.5 0 0 1 .276-.447l8-4a.5.5 0 0 1 .487.022zM6 8.694 1 10.36V15h5V8.694zM7 15h2v-1.5a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 .5.5V15h2V1.309l-7 3.5V15z"/>
                                        <path d="M2 11h1v1H2v-1zm2 0h1v1H4v-1zm-2 2h1v1H2v-1zm2 0h1v1H4v-1zm4-4h1v1H8V9zm2 0h1v1h-1V9zm-2 2h1v1H8v-1zm2 0h1v1h-1v-1zm2-2h1v1h-1V9zm0 2h1v1h-1v-1zM8 7h1v1H8V7zm2 0h1v1h-1V7zm2 0h1v1h-1V7zM8 5h1v1H8V5zm2 0h1v1h-1V5zm2 0h1v1h-1V5zm0-2h1v1h-1V3z"/>
                                    </svg> 
                                    <span class="ms-3">Organisation</span>
                                </button>

                                <div id="organisationCollapse" class="collapse">
                                    <ul class="list-unstyled">
                                        <li><a class="btn px-3 mt-2 rounded w-90 mt-2 sidebar-btn" href="<?= CONTROLLERS_URL ?>admin/gestionOrganisation.php">Gestion organisation</a></li>
                                    </ul>
                                </div>
                            
                            </li>
                            <!-- PROJETS -->
                            <li class="mt-2">
                                <button class="btn px-3 mt-2 rounded w-90 mt-2 sidebar-btn" data-bs-toggle="collapse" data-bs-target="#projetsCollapse" aria-expanded="false">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-briefcase-fill ms-2" viewBox="0 0 16 16">
                                        <path d="M6.5 1A1.5 1.5 0 0 0 5 2.5V3H1.5A1.5 1.5 0 0 0 0 4.5v1.384l7.614 2.03a1.5 1.5 0 0 0 .772 0L16 5.884V4.5A1.5 1.5 0 0 0 14.5 3H11v-.5A1.5 1.5 0 0 0 9.5 1h-3zm0 1h3a.5.5 0 0 1 .5.5V3H6v-.5a.5.5 0 0 1 .5-.5z"/>
                                        <path d="M0 12.5A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5V6.85L8.129 8.947a.5.5 0 0 1-.258 0L0 6.85v5.65z"/>
                                    </svg>
                                    <span class="ms-3">Projets</span>
                                </button>

                                <div id="projetsCollapse" class="collapse">
                                    <ul class="list-unstyled">
                                        <li><a class="btn px-3 mt-2 rounded w-90 mt-2 sidebar-btn" href="<?= CONTROLLERS_URL ?>admin/projets.php">Vue d'ensemble</a></li>
                                        <li><a class="btn px-3 mt-2 rounded w-90 mt-2 sidebar-btn" href="<?= CONTROLLERS_URL ?>admin/creationProjets.php">Création de projets</a></li>
                                        <li><a class="btn px-3 mt-2 rounded w-90 mt-2 sidebar-btn" href="<?= CONTROLLERS_URL ?>admin/listeProjets.php">Liste des projets</a></li>
                                    </ul>
                                </div>
                            
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </nav>

        <?php if($pageName != 'map.php') { ?>
        <!-- SIDE BAR -->
        <div class="row w-100" style="height: 100%;">
            <div id="sideBar" class="navbar-expand-lg col-md-0 col-lg-2 p-0 collapse show">
                <div class="collapse navbar-collapse align-items-start text-center pt-2 ps-2" style="background-color: rgba(31,41,55,1);height: 100%; border: 1px solid rgba(0, 0, 0, 0.125);">
                    <nav class="w-100 h-100">
                        <ul class="list-unstyled position-relative" style="height: 100%;">
                            <!-- LOGO -->
                            <div class="overflow-y" style="height: 100%; max-height: 85vh">
                                <li class="mt-2">
                                    <a class="navbar-brand text-light mb-1" href="<?= ROOT_URL ?>index.php">
                                        <img src="<?= IMG_URL ?>logo.png" width="35" height="35" class="d-inline-block align-top ms-3 me-2" alt="">
                                        Stories Helper
                                    </a>
                                </li>

                                <li class="mt-3">
                                    <a href="<?= CONTROLLERS_URL ?>admin/index.php" class="btn px-3 mt-2 w-90 sidebar-btn">
                                        <div class="mx-auto" style="width: max-content;">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="sidebar-icon bi bi-house-fill" viewBox="0 0 16 16">
                                                <path fill-rule="evenodd" d="m8 3.293 6 6V13.5a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 2 13.5V9.293l6-6zm5-.793V6l-2-2V2.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5z"/>
                                                <path fill-rule="evenodd" d="M7.293 1.5a1 1 0 0 1 1.414 0l6.647 6.646a.5.5 0 0 1-.708.708L8 2.207 1.354 8.854a.5.5 0 1 1-.708-.708L7.293 1.5z"/>
                                            </svg>
                                            <span class="ms-3" style="float:left">Accueil</span>
                                        </div>
                                    </a>
                                </li>
                                <hr class="border-bottom w-75 mx-auto">
                                <!-- COLLABORATEURS -->
                                <li class="mt-3">
                                    <button style="min-width: max-content;" id="collaborateurs-btn" class="btn w-90 px-3 rounded sidebar-btn" data-bs-toggle="collapse" data-bs-target="#collaborateursCollapse" aria-expanded="false">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="sidebar-icon bi bi-person-circle ms-2" viewBox="0 0 16 16">
                                            <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/>
                                            <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z"/>
                                        </svg>
                                        <span class="ms-3" style="float: left;">Collaborateurs</span>
                                    </button>

                                    <div id="collaborateursCollapse" class="collapse">
                                        <ul class="list-unstyled">
                                            <li><a class="btn px-3 mt-2 rounded w-90 mt-2 sidebar-btn" href="<?= CONTROLLERS_URL ?>admin/collaborateurs.php">Vue d'ensemble</a></li>
                                            <li><a class="btn px-3 mt-2 rounded w-90 mt-2 sidebar-btn" href="<?= CONTROLLERS_URL ?>admin/inscriptionUtilisateur.php">Inscriptions</a></li>
                                            <li><a class="btn px-3 mt-2 rounded w-90 mt-2 sidebar-btn" href="<?= CONTROLLERS_URL ?>admin/listeMembres.php">Liste des membres</a></li>
                                        </ul>
                                    </div>
                                </li>
                                <hr class="border-bottom w-75 mx-auto">
                                <!-- ORGANISATION -->
                                <li class="mt-3">
                                    <button style="min-width: max-content;" id="organization-btn" class="btn px-3 mt-2 rounded w-90 sidebar-btn" data-bs-toggle="collapse" data-bs-target="#organisationCollapse" aria-expanded="false">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="sidebar-icon bi bi-building ms-2" viewBox="0 0 16 16">
                                            <path fill-rule="evenodd" d="M14.763.075A.5.5 0 0 1 15 .5v15a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1-.5-.5V14h-1v1.5a.5.5 0 0 1-.5.5h-9a.5.5 0 0 1-.5-.5V10a.5.5 0 0 1 .342-.474L6 7.64V4.5a.5.5 0 0 1 .276-.447l8-4a.5.5 0 0 1 .487.022zM6 8.694 1 10.36V15h5V8.694zM7 15h2v-1.5a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 .5.5V15h2V1.309l-7 3.5V15z"/>
                                            <path d="M2 11h1v1H2v-1zm2 0h1v1H4v-1zm-2 2h1v1H2v-1zm2 0h1v1H4v-1zm4-4h1v1H8V9zm2 0h1v1h-1V9zm-2 2h1v1H8v-1zm2 0h1v1h-1v-1zm2-2h1v1h-1V9zm0 2h1v1h-1v-1zM8 7h1v1H8V7zm2 0h1v1h-1V7zm2 0h1v1h-1V7zM8 5h1v1H8V5zm2 0h1v1h-1V5zm2 0h1v1h-1V5zm0-2h1v1h-1V3z"/>
                                        </svg> 
                                        <span class="ms-3" style="float: left;">Organisation</span>
                                    </button>

                                    <div id="organisationCollapse" class="collapse">
                                        <ul class="list-unstyled">
                                            <li><a class="btn px-3 mt-2 rounded w-90 mt-2 sidebar-btn" href="<?= CONTROLLERS_URL ?>admin/gestionOrganisation.php">Gestion organisation</a></li>
                                        </ul>
                                    </div>
                                </li>
                                <hr class="border-bottom w-75 mx-auto">
                                <!-- PROJETS -->
                                <li class="mt-3">
                                    <button style="min-width: max-content;" id="projets-btn" class="btn px-3 mt-2 rounded w-90 sidebar-btn" data-bs-toggle="collapse" data-bs-target="#projetsCollapse" aria-expanded="false">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="sidebar-icon bi bi-briefcase-fill ms-2" viewBox="0 0 16 16">
                                            <path d="M6.5 1A1.5 1.5 0 0 0 5 2.5V3H1.5A1.5 1.5 0 0 0 0 4.5v1.384l7.614 2.03a1.5 1.5 0 0 0 .772 0L16 5.884V4.5A1.5 1.5 0 0 0 14.5 3H11v-.5A1.5 1.5 0 0 0 9.5 1h-3zm0 1h3a.5.5 0 0 1 .5.5V3H6v-.5a.5.5 0 0 1 .5-.5z"/>
                                            <path d="M0 12.5A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5V6.85L8.129 8.947a.5.5 0 0 1-.258 0L0 6.85v5.65z"/>
                                        </svg>
                                        <span class="ms-3" style="float:left">Projets</span>
                                    </button>

                                    <div id="projetsCollapse" class="collapse">
                                        <ul class="list-unstyled">
                                            <li>
                                                <a class="btn px-3 mt-2 rounded w-90 mt-2 sidebar-btn" href="<?= CONTROLLERS_URL ?>admin/projets.php">
                                                    Vue d'ensemble
                                                </a>
                                            </li>
                                            <li>
                                                <a class="btn px-3 mt-2 rounded w-90 mt-2 sidebar-btn" href="<?= CONTROLLERS_URL ?>admin/creationProjets.php">
                                                    Création de projets
                                                </a>
                                            </li>
                                            <li>
                                                <a class="btn px-3 mt-2 rounded w-90 mt-2 sidebar-btn" href="<?= CONTROLLERS_URL ?>admin/listeProjets.php">
                                                    Liste des projets
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                            </div>

                            <!-- DISCONNECT -->
                            <li class="mb-4 position-absolute bottom-0 w-100">
                                <a class="btn h-12 px-3 mt-2 rounded w-90 sidebar-btn danger" href="<?= CONTROLLERS_URL ?>visiteur/deconnexion.php">Déconnexion</a>
                            </li>

                        </ul>
                    </nav>
                </div>
            </div>
            <div id="main" class="pt-3 px-3 position-relative">
                <i id="close-sidebar" class="btn btn-outline-dark bi bi-arrow-bar-left position-absolute start-0 w-auto collapse show before"></i>
                <i id="open-sidebar" class="btn btn-outline-dark bi bi-arrow-bar-right position-absolute start-0 w-auto collapse before"></i>
            <?php } ?>