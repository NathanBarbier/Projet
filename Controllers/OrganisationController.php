<?php
require_once "header.php";

$action = $_GET["action"] ? $_GET["action"] : false;

$idOrganisation = $_SESSION["idOrganisation"] ? $_SESSION["idOrganisation"] : false;

$Organisation = new Organisation($idOrganisation);

$InfosOrganisation = [
    "nom" => $Organisation->getNom(),
    "email" => $Organisation->getEmail(),
    "nombreEmployes" => $Organisation->countUsers(),
    "nombreEquipes" => $Organisation->countEquipes(),
];

if($action == "deleteOrganisation")
{
    $Organisation->delete();
    header(ROOTPATH.DIRECTORY_SEPARATOR."index.php");
}

?>