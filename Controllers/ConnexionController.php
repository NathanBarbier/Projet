<?php require_once "header.php";

$rights = $_SESSION["habilitation"] ?? false;
$idUser = $_SESSION["idUtilisateur"] ?? false;

$envoi = $_POST["envoi"] ?? false;

$email = $_POST["email"] ?? false;
$mdp = $_POST["mdp"] ?? false;

$User = new User();
$Organisation = new Organisation();

if($envoi)
{
    if($email && $mdp)
    {
        if(filter_var($email, FILTER_VALIDATE_EMAIL))
        {
            if($User->verifEmail($email) == true)
            {
                $utilisateur = $User->fetchByEmail($email);
                if(password_verify($mdp, $utilisateur["mdp"]))
                {
                    $_SESSION["habilitation"] = "user";
                    $_SESSION["idUtilisateur"] = $utilisateur["idUtilisateur"];
                    header("location:".ROOT_PATH."index.php");
                } 
                else 
                {
                    header("location:".VIEWS_PATH."general/connexion.php?error=mdpincorrect");
                }
            } 
            
            if($Organisation->verifEmail($email) == true) 
            {
                $utilisateur = $Organisation->fetchByEmail($email);
                if(password_verify($mdp, $utilisateur["mdp"]))
                {
                    $_SESSION["habilitation"] = "admin";
                    $_SESSION["idOrganisation"] = $utilisateur["idOrganisation"];
                    $_SESSION["email"] = $utilisateur["email"];
                    
                    header("location:".ROOT_PATH."index.php");
                } 
                else 
                {
                    header("location:".VIEWS_PATH."general/connexion.php?error=mdpincorrect");
                }
            }

            if(($User->verifEmail($email) == false && $Organisation->verifEmail($email) == false))
            {
                header("location:".VIEWS_PATH."general/connexion.php?error=idincorrect");
            }
            
        } 
        else 
        {
            header("location:".VIEWS_PATH."general/connexion.php?error=invalidemail");
        }
    } 
    else 
    {
        header("location:".VIEWS_PATH."general/connexion.php?error=champsvide");
    }

} 
else 
{
    header("location:".ROOT_PATH."index.php");
}