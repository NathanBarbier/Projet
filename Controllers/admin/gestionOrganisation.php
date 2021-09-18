<?php
//import all models
require_once "../../traitements/header.php";

$action = GETPOST('action');

$idOrganisation = $_SESSION["idOrganisation"] ?? false;
$rights = $_SESSION["habilitation"] ?? false;

$Organisation = new Organisation($idOrganisation);

$tpl = "gestionOrganisation.php";
$data = new stdClass;

if($rights === "admin")
{
    
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
}
else
{
    header("location:".ROOT_URL."index.php");
}


?>