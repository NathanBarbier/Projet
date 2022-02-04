<?php
$idOrganization = $_SESSION["idOrganization"] ?? false;
$idUser = $_SESSION['idUser'] ?? false;
$rights = $_SESSION["rights"] ?? false;

if($rights != "admin" || !$idUser || !$idOrganization)
{
    header("location:".ROOT_URL."index.php");
    exit;
}
?>