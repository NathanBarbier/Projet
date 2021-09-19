<?php
require_once "../../traitements/header.php";

$idOrganisation = $_SESSION["idOrganisation"] ?? false;
$rights = $_SESSION["habilitation"] ?? false;

if($rights === "admin")
{
    $idEquipe = GETPOST('idEquipe');
    
    $Equipe = new Equipe($idEquipe);
    
    // var_dump($Equipe);
    // exit;
    
    $tpl = "infoEquipe.php";
    
    $data = new stdClass;
    
    $equipe = array(
        'id' => $Equipe->getId(),
        'nom' => $Equipe->getNom(),
        'idChef' => $Equipe->getChef(),
        'membres' => $Equipe->getMembres(),
    );
    
    $User = new User($Equipe->getChef());
    
    $chefEquipe = array(
        'nom' => $User->getFirstname(),
        'prenom' => $User->getLastname(),
    );
    
    $data = array(
        'Equipe' => $equipe,
        'ChefEquipe' => $chefEquipe,
    );
    
    $data = json_encode($data);
    
    header("location:".VIEWS_URL."admin/".$tpl."?data=$data");
}
else
{
    header("location:".ROOT_URL."index.php");
}


?>