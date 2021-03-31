<?php require_once "header.php";
if(!empty($_POST) && !empty($_GET["idUser"]) && !empty($_GET["idEquipe"]) && !empty($_SESSION["habilitation"]) && $_SESSION["habilitation"] == "admin")
{
    extract($_POST);
    extract($_GET);
    try {
        modifierEquipeUtilisateur($idEquipe,$idUser);
        header("../admin/listeMembres.php?success=modifierEquipe");
    } catch (exception $e) {
        header('location:../admin/listeMembres.php?error=ModifEquipeFatalError');
    }
} else {
    header("location:../index.php");
}
?>