<?php
// require_once "../../traitements/header.php";

$rights = $_SESSION["rights"] ?? false;

if($rights)
{
    header("location:".ROOT_URL."index.php");
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">

    <title>Stories Helper</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
    <link rel="stylesheet" href="<?= ROOT_URL ?>style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>

</head>
<body>
<nav class="navbar navbar-dark navbar-expand-md bg-dark">

    <a class="navbar-brand mb-1" href="<?= ROOT_URL ?>index.php">
        <img src="<?= IMG_URL ?>logo.png" width="35" height="35" class="d-inline-block align-top ms-3 me-2" alt="">
        Stories Helper
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <?php 
    if($pageName != "inscriptionOrganisation.php")
    { ?>
    <div class="collapse navbar-collapse" id="navbarNavDropdown">
        <div class="navbar-nav w-100" style="padding-right: 5vh;">
            <a class="nav-item nav-link ms-auto" href="<?php CONTROLLERS_URL ?>inscriptionOrganisation.php">Inscription</a>
        </div>
    </div>
    <?php
    }
    if($pageName != "connexion.php")
    { ?>
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <div class="navbar-nav w-100" style="padding-right: 5vh;">
                <a class="nav-item nav-link ms-auto" href="<?= CONTROLLERS_URL ?>general/connexion.php">Connexion</a>
            </div>
        </div>
    <?php
    } ?>

</nav>
<div class="container mt-4">