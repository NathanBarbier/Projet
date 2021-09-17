<?php

$rights = $_SESSION["habilitation"] ?? false;
$idUser = $_SESSION["idUtilisateur"] ?? false;

$envoi = $_POST["envoi"] ?? false;

$email = $_POST["email"] ?? false;
$mdp = $_POST["mdp"] ?? false;

$User = new User();
$Organisation = new Organisation();

$erreurs = [];

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
                    $erreurs[] = "Le mot de passe est incorrect.";
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
                    $erreurs[] = "Le mot de passe est incorrect.";
                }
            }

            if((!$User->verifEmail($email) && !$Organisation->verifEmail($email)))
            {
                $erreurs[] = "Cette adresse email n'est associée à aucun compte.";
            }
            
        } 
        else 
        {
            $erreurs[] = "Le format de l'adresse email est incorrect.";
        }
    } 
    else 
    {
        $erreurs[] = "Un champs n'a pas été rempli.";
    }

} 
// else 
// {
//     header("location:".ROOT_PATH."index.php");
// }