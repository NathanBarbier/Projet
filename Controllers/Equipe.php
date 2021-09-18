<?php
//import all models
require_once "../traitements/header.php";

$idOrganisation = $_SESSION["idOrganisation"] ?? false;
$rights = $_SESSION["habilitation"] ?? false;

$action = $_GET["action"] ?? false;
$idEquipe = $_GET["idEquipe"] ?? false;

$envoi = $_POST["envoi"] ?? false;

$Equipe = new Equipe($idEquipe);

$equipe = $Equipe->fetch($idEquipe);
$nbMembresEquipe = $Equipe->countMembres();
$membresEquipe = $Equipe->countMembres();
$chefEquipe = $Equipe->getChef();

$erreurs = array();
$success = array();

$data = array();

if($rights == "admin")
{
    if($envoi)
    {
        try
        {
            $Equipe->create($nomEquipe, $idOrganisation);
        } 
        catch(exception $e) 
        {
            // header("location:".VIEWS_PATH."admin/gererEntreprise.php?error=fatalerror");
            $erreurs[] = "Une erreur innatendue est survenue.";
            
        }
        // header("location:".VIEWS_PATH."admin/gererEntreprise.php?success=ajouterEquipe");
        if(sizeof($erreurs) == 0)
        {
            $success[] = "L'équipe a été ajoutée avec succès.";
        }
    } 
    // else 
    // {
        // header("location:".VIEWS_PATH."admin/gererEntreprise.php");
    // }
} 
else 
{
    header("location:".ROOT_PATH."index.php");
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

?>

