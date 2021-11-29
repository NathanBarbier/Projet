<?php require_once '../../services/header.php';
$rights = $_SESSION["rights"] ?? false;
if($rights == "admin" OR $rights == "user")
{
    header("location:".ROOT_URL."index.php");
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">

    <title>Stories Helper</title>

    <link rel="icon" href="<?= IMG_URL ?>logo.png">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
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

    <div class="collapse navbar-collapse" id="navbarNavDropdown">
        <div class="navbar-nav w-100" style="padding-right: 5vh;">
    <?php 
    if($pageName != "needConsent.php")
    {
        if($pageName != "inscriptionOrganisation.php") { ?>
            <a class="nav-item nav-link ms-auto" href="<?php CONTROLLERS_URL ?>inscriptionOrganisation.php">Inscription</a>
        <?php }
        if($pageName != "connexion.php" && $pageName != "Connexion.php") { ?>
            <a class="nav-item nav-link ms-auto" href="<?= CONTROLLERS_URL ?>general/connexion.php">Connexion</a>
        <?php }
    } ?>
        </div>
    </div>

</nav>
<div class="container mt-4">