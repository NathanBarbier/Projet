<?php require_once "header.php";

if(!empty($_SESSION['habilitation']) && $_SESSION['habilitation'] == 1)
{
    if(!empty($_GET['idProjet']) && !empty($_GET['idEquipe']))
    {
        extract($_GET);
        // AJOUTER L EQUIPE SELECTONNEE AU PROJET DANS LA TABLE TRAVAILLE SUR
        try {
            ajoutEquipeAuProjet($idEquipe, $idProjet);
            header("location:../admin/creationProjets.php?idProjet=$idProjet&success=ajoutEquipe");
        } catch (exception $e) {
            header("location:../admin/creationProjets.php?idProjet=$idProjet&error=ajoutEquipeFatalError");
        }
    }
} else {
    header("location:../index.php");
}
?>