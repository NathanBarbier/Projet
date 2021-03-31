<?php require_once "header.php";
if(!empty($_POST) && !empty($_GET["idUser"]) && !empty($_GET["idPoste"]) && !empty($_SESSION["habilitation"]) && $_SESSION["habilitation"] == "admin")
{
    extract($_POST);
    extract($_GET);
    try {
        modifierPosteUtilisateur($idPoste,$idUser);
        header("../admin/listeMembres.php?success=modifierPoste");
    } catch (exception $e) {
        header('location:../admin/listeMembres.php?error=ModifPosteFatalError');
    }
} else {
    header("location:../index.php");
}
?>