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
    <link rel="stylesheet" href="style.css">

</head>
<body>
<nav class="navbar navbar-dark navbar-expand-md bg-dark">

    <a class="navbar-brand" href="../index.php">
        <img src="<?= IMG_URL ?>logo.png" width="30" height="30" class="d-inline-block align-top" alt="">
        Projet gestion projets
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNavDropdown">
        <div class="navbar-nav w-100" style="padding-right: 5vh;">

            <div style="margin-left:auto">
                <a class="btn btn-danger" href="../traitements/deconnexion.php" >Déconnexion</a>
            </div>

        </div>
    </div>

</nav>

<div class="container mt-4" style="width: 50%">

<div class="container mt-4">
<?php
} 
else 
{
    header("location:".ROOT_URL."index.php");
}
?>
