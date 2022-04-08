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
        <link rel="stylesheet" href="<?= ASSETS_URL ?>style.min.css">
        <!-- jQuery cdn -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous" defer></script>
        <!-- Toastr cdn -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.min.js" integrity="sha512-lbwH47l/tPXJYG9AcFNoJaTMhGvYWhVM9YI43CT+uteTRRaiLCui8snIgyAN8XWgNjNhCqlAUdzZptso6OCoFQ==" crossorigin="anonymous" referrerpolicy="no-referrer" defer></script>
    </head>
    
    <body>
    <?php if($pageName != 'map.php') { ?>
    <nav class="navbar navbar-dark bg-dark pt-1" style="height: 55px;">
        <a class="navbar-brand mb-1" href="<?= ROOT_URL ?>index.php">
            <img src="<?= IMG_URL ?>logo.png" width="35" height="35" class="d-inline-block align-top ms-3 me-2" alt="">
            Stories Helper
        </a>
        <div class="me-4" style="margin-left:auto">
            <a class="btn btn-outline-danger btn-sm" href="<?= CONTROLLERS_URL ?>visitor/signout.php" >Déconnexion</a>
        </div>
    </nav>
    <?php } ?>