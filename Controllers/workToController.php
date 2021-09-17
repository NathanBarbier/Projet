<?php require_once "header.php";

$action = $_GET["action"] ?? false;
$idProjet = $_GET["idProjet"] ?? false;
$idEquipe = $_GET["idEquipe"] ?? false;

$rights = $_SESSION["habilitation"] ?? false;

$WorkTo = new WorkTo();

if($rights)
{
    if($idEquipe && $idProjet)
    {
        try
        {
            $WorkTo->create($idEquipe, $idProjet);
        } 
        catch (exception $e) 
        {
            header("location:".VIEWS_PATH."admin/creationProjets.php?idProjet=$idProjet&error=ajoutEquipeFatalError");
        }
        header("location:".VIEWS_PATH."admin/creationProjets.php?idProjet=$idProjet&success=ajoutEquipe");
    }
} 
else 
{
    header("location:".ROOT_PATH."index.php");
}
?>