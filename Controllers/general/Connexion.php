<?php
//import all models
require_once "../../traitements/header.php";

$rights = $_SESSION["habilitation"] ?? false;
$idUser = $_SESSION["idUtilisateur"] ?? false;

$envoi = GETPOST('envoi');

$email = GETPOST('email');
$mdp = GETPOST('mdp');

$User = new User();
$Organisation = new Organisation();

$erreurs = array();
$success = false;

$data = array();

$tpl = "connexion.php";

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

                    // exit;
                    $_SESSION["habilitation"] = "user";
                    $_SESSION["idUtilisateur"] = intval($utilisateur["idUtilisateur"]);
                    
                    $success = true;
                    // header("location:".ROOT_PATH."index.php");
                } 
                else 
                {
                    $erreurs[] = "Le mot de passe est incorrect.";
                }
            } 
            
            if(!$success)
            {
                if($Organisation->verifEmail($email) == true) 
                {
                    $utilisateur = $Organisation->fetchByEmail($email);
                    if(password_verify($mdp, $utilisateur["mdp"]))
                    {
                        $_SESSION["habilitation"] = "admin";
                        $_SESSION["idOrganisation"] = $utilisateur["idOrganisation"];
                        $_SESSION["email"] = $utilisateur["email"];
                        
                        $success = true;
                        // header("location:".ROOT_URL."index.php");
                    } 
                    else 
                    {
                        $erreurs[] = "Le mot de passe est incorrect.";
                    }
                }
            }

            if(!$success)
            {
                if((!$User->verifEmail($email) && !$Organisation->verifEmail($email)))
                {
                    $erreurs[] = "Cette adresse email n'est associée à aucun compte.";
                }
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

$data = array(
    'erreurs' => $erreurs,
);

$data = json_encode($data);

// var_dump($success);
// exit;

if(!$success)
{
    header("location:".VIEWS_URL."general/".$tpl."?data=$data");
}
else
{
    header("location:".ROOT_URL."index.php?data=$data");
}