<?php require_once "header.php";
if(!empty($_POST["envoi"]))
{
    extract($_POST);
    if(!empty($mail) && !empty($mdp))
    {
        if(filter_var($mail, FILTER_VALIDATE_EMAIL))
        {
            if(verifEmailUtilisateur($mail) == true)
            {
                $utilisateur = recupUtilisateurMail($mail);
                if(password_verify($mdp, $utilisateur["mdp"]))
                {
                    $_SESSION["habilitation"] = "user";
                    $_SESSION["idUtilisateur"] = $utilisateur["idUtilisateur"];
                    $_SESSION["prenom"] = $utilisateur["prenom"];
                    $_SESSION["nom"] = $utilisateur["nom"];
                    $_SESSION["email"] = $utilisateur["email"];
                    header("location:../index.php");
                } else {
                    header("location:../pages/connexion.php?error=mdpincorrect");
                }
            } 
            
            if(verifEmailOrganisation($mail) == true) {
                $utilisateur = recupOrganisationMail($mail);
                if(password_verify($mdp, $utilisateur["mdp"]))
                {
                    $utilisateur = recupOrganisationMail($mail);
                    $_SESSION["habilitation"] = "admin";
                    $_SESSION["idOrganisation"] = $utilisateur["idOrganisation"];
                    $_SESSION["email"] = $utilisateur["email"];
                    print_r($_SESSION);
                    
                    header("location:../index.php");
                } else {
                    header("location:../pages/connexion.php?error=mdpincorrect");
                }
            }

            if((verifEmailUtilisateur($mail) == false && verifEmailOrganisation($mail) == false))
            {
                header("location:../pages/connexion.php?error=idincorrect");
            }
            
        } else {
            header("location:../pages/connexion.php?error=invalidemail");
        }
    } else {
        header("location:../pages/connexion.php?error=champsvide");
    }

} else {
    header("location:../index.php");
}