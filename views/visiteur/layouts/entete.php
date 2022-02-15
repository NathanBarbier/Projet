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
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>
<body>
    <nav class="navbar navbar-dark bg-dark w-100" style="height: 60px; z-index : 999">
        <a class="navbar-brand mb-1" href="<?= ROOT_URL ?>index.php">
            <img src="<?= IMG_URL ?>logo.png" width="35" height="35" class="d-inline-block align-top ms-3 me-2" alt="">
            Stories Helper
        </a>
    </nav>
<div class="container mt-4">