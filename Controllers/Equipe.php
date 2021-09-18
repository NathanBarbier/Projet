<?php
//import all models
require_once "../traitements/header.php";

$idOrganisation = $_SESSION["idOrganisation"] ?? false;
$rights = $_SESSION["habilitation"] ?? false;

if($rights == "admin")
{
    $action = GETPOST('action');
    $idEquipe = GETPOST('idEquipe');
    $envoi = GETPOST('envoi');

    $Equipe = new Equipe($idEquipe);

    $equipe = $Equipe->fetch($idEquipe);
    $nbMembresEquipe = $Equipe->countMembres();
    $membresEquipe = $Equipe->countMembres();
    $chefEquipe = $Equipe->getChef();

    $erreurs = array();
    $success = array();

    $data = new stdClass;


    if($envoi)
    {
        try
        {
            $Equipe->create($nomEquipe, $idOrganisation);
        } 
        catch(exception $e) 
        {
            $erreurs[] = "Une erreur innatendue est survenue.";
            
        }
        if(sizeof($erreurs) == 0)
        {
            $success[] = "L'équipe a été ajoutée avec succès.";
        }
    } 


    $data = array(
        'erreurs' => $erreurs,
        'success' => $success,
        'equipe' => $equipe,
        'nbMembresEquipe' => $nbMembresEquipe,
        'membresEquipe' => $membresEquipe,
        'chefEquipe' => $chefEquipe,
    );

    $data = json_encode($data);

    header("location".VIEWS_URL.$tpl."?data=$data");

} 
else 
{
    header("location:".ROOT_PATH."index.php");
} 

?>

