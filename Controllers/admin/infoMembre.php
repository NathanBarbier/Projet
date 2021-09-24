<?php
require_once "../../traitements/header.php";

$idOrganization = $_SESSION["idOrganization"] ?? false;
$rights = $_SESSION["rights"] ?? false;

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