<?php
//import all models
require_once "../../traitements/header.php";

$action = $_GET["action"] ?? false;

$idOrganisation = $_SESSION["idOrganisation"] ?? false;

$Organisation = new Organisation($idOrganisation);

$data = array();

$InfosOrganisation = [

];

$tpl = "gestionOrganisation.php";

if($action == "deleteOrganisation")
{
    $Organisation->delete();
    header("location:".ROOT_URL."index.php");
}

$data = array(
    "nomOrganisation" => $Organisation->getNom(),
    "emailOrganisation" => $Organisation->getEmail(),
    "nombreEmployes" => $Organisation->countUsers(),
    "nombreEquipes" => $Organisation->countEquipes(),
);

$data = json_encode($data);

header("location:".VIEWS_URL."admin/".$tpl."?data=$data");

?>