<?php
// require_once "../../traitements/header.php";
$rights = $_SESSION["rights"] ?? false;

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

        <title>Stories Helper</title>

        <link rel="icon" href="<?= IMG_URL ?>logo.png">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
        <link rel="stylesheet" href="<?= ROOT_URL ?>style.css">

        <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    </head>
    <body style="overflow:hidden">
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

                <div style="margin-left:auto">
                    <a class="btn btn-danger" href="<?= CONTROLLERS_URL ?>general/Deconnexion.php" >Déconnexion</a>
                </div>

            </div>
        </div>

    </nav>

        <!-- SIDE BAR -->
    <?php if($pageName != "map.php")
    { ?>
    <div class="row" style="height: 100%;">
        <div class="col-2">
        </div>

        <div class="text-center pt-2 bg-light col-2" style="height: 95vh; border: 1px solid rgba(0, 0, 0, 0.125); position: fixed">
            <nav>
                <ul class="list-unstyled">
                    <li><a href="<?= VIEWS_URL ?>admin/index.php" class="btn btn-outline-secondary w-75">Accueil</a></li>

                    <!-- TABLEAU DE BORD -->
                    <li class="mt-2">
                        <a href="<?= CONTROLLERS_URL ?>membres/tableauDeBord.php" class="btn btn-outline-primary w-75 mt-4" >Tableau de bord</a>
                    </li>
                </ul>
            </nav>

        </div>
    <?php
    }
} 
else 
{
    header("location:".ROOT_URL."index.php");
}
?>
