<?php

$idOrganisation = $_SESSION["idOrganisation"] ?? false;
$rights = $_SESSION["habilitation"] ?? false;

$action = $_GET["action"] ?? false;
$idEquipe = $_GET["idEquipe"] ?? false;

$envoi = $_POST["envoi"] ?? false;

$Equipe = new Equipe($idEquipe);

$equipe = $Equipe->fetch($idEquipe);
$nmMembresEquipe = $Equipe->countMembres();
$membresEquipe = $Equipe->countMembres();
$chefEquipe = $Equipe->getChef();

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
            header("location:".VIEWS_PATH."admin/gererEntreprise.php?error=fatalerror");
            
        }
        header("location:".VIEWS_PATH."admin/gererEntreprise.php?success=ajouterEquipe");
    } 
    else 
    {
        header("location:".VIEWS_PATH."admin/gererEntreprise.php");
    }

} 
else 
{
    header("location:".ROOT_PATH."index.php");
} 
?>

