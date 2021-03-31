<?php require_once "header.php";
if(!empty($_POST) && !empty($_GET["idUser"]) && !empty($_POST['prenom']) && !empty($_SESSION["habilitation"]) && $_SESSION["habilitation"] == "admin")
{
    if(!empty($_POST['envoi']) && $_POST['envoi'] == true)
    {
        extract($_POST);
        extract($_GET);
        $utilisateur = recupInfosUtilisateur($idUser);
    
        if($prenom != $utilisateur['prenom'])
        {
            try {
                modifierPrenom($prenom, $idUser);
                header("location:../admin/listeMembres.php?success=modifierPrenom");
            } catch (exception $e) {
                header('location:../admin/listeMembres.php?error=modifPrenomFatalError');
            }
        } else {
            header("location:../admin/listeMembres.php?error=surnameNoChange");
        }
    } else {
        header("location:../index.php");
    }
} else {
    header("location:../index.php");
}
?>