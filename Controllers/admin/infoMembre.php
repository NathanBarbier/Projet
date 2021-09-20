<?php
require_once "../../traitements/header.php";

$idOrganisation = $_SESSION["idOrganisation"] ?? false;
$rights = $_SESSION["habilitation"] ?? false;

if($rights === "admin")
{
    $idUser = GETPOST('idUser');
    
    $User = new User($idUser);

    
    $data = new stdClass;
    
    $tpl = "infoMembre.php";
    

    
    $data = array(
        
    );
    
    $data = json_encode($data);
    
    header("location:".VIEWS_URL."admin/".$tpl."?data=$data");
}
else
{
    header("location:".ROOT_URL."index.php");
}


?>