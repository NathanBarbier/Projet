<?php
require_once "../../traitements/header.php";

$idOrganisation = $_SESSION["idOrganisation"] ?? false;
$rights = $_SESSION["habilitation"] ?? false;

if($rights === "admin")
{
    $idUser = GETPOST('idUser');
    
    $User = new User($idUser);

    $tpl = "infoMembre.php";

    require_once VIEWS_PATH."admin/".$tpl;
}
else
{
    header("location:".ROOT_URL."index.php");
}


?>