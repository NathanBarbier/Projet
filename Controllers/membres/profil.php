<?php
//import all models
require_once "../../traitements/header.php";

$idUser = $_SESSION["idUtilisateur"] ?? null;
$rights = $_SESSION["habilitation"] ?? false;
$idOrganisation = $_SESSION["idOrganisation"] ?? false;

if($rights === "user")
{    
    $User = new User($idUser);
    $Poste = new Poste($User->getIdPoste());
    $Equipe = new Equipe($User->getIdEquipe());
    
    $tpl = "profil.php";
    
    $erreurs = array();
    $success = false;
    
    $data = new stdClass;
    
    
    $data = array(
        // 'avatar' => $User->getAvatar(),
        'firstname' => $User->getFirstname(),
        'lastname' => $User->getLastname(),
        'email' => $User->getEmail(),
        'nomPoste' => $Poste->getNom(),
        'nomEquipe' => $Equipe->getNom(),
    );
    
    $data = json_encode($data);
    
    header("location:".VIEWS_URL."membres/".$tpl."?data=$data");
}
else
{
    header("location:".ROOT_URL."index.php");
}

?>