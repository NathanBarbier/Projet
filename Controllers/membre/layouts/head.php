<?php
$idOrganization = $_SESSION["idOrganization"] ?? false;
$idUser = $_SESSION['idUser'] ?? false;
$rights = $_SESSION["rights"] ?? false;

if($rights != "user" || !$idUser || !$idOrganization)
{
    header("location:".ROOT_URL."index.php");
    exit;
}
?>