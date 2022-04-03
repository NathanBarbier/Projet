<?php
$idOrganization = $_SESSION["idOrganization"] ?? false;
$idUser = $_SESSION['idUser'] ?? false;
$rights = $_SESSION["rights"] ?? false;
// get the user ip adress
$ip = $_SERVER['REMOTE_ADDR'];

if($rights != "admin" || !$idUser || !$idOrganization)
{
    header("location:".ROOT_URL."index.php");
    exit;
}
?>