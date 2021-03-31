<?php require_once "header.php";
if(!empty($_POST) && !empty($_GET["idUser"]) && !empty($_POST['nom']) && !empty($_SESSION["habilitation"]) && $_SESSION["habilitation"] == "admin")
{
    // exit;
    if(!empty($_POST['envoi']) && $_POST['envoi'] == true)
    {
        // exit;
        extract($_POST);
        extract($_GET);
        $utilisateur = recupInfosUtilisateur($idUser);
    
        if($nom != $utilisateur['nom'])
        {
            try {
                modifierNom($nom, $idUser);
                header("location:../admin/listeMembres.php?success=modifierNom");
            } catch (exception $e) {
                header('location:../admin/listeMembres.php?error=modifNomFatalError');
            }
        } else {
            header("location:../admin/listeMembres.php?error=nameNoChange");
        }
    } else {
        header("location:../index.php");
    }
} else {
    header("location:../index.php");
}
?>