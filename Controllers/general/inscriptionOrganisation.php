<?php
//import all models
require_once "../../traitements/header.php";

// $idOrganisation = $_SESSION["idOrganisation"] ?? false;
$action = GETPOST('action');
$envoi = GETPOST('envoi');
$email = GETPOST('email');
$nom = GETPOST('nom');
$mdp = GETPOST('mdp');
$mdp2 = GETPOST('mdp2');

$Organisation = new Organisation();
$Inscription = new Inscription();

$erreurs = array();
$success = false;

$data = array();

if($action == "inscriptionOrg")
{
    $tpl = "general/inscriptionOrganisation.php";
    
    if($envoi) 
    {
        if($nom && $email && $mdp && $mdp2)
        {
            if($Organisation->verifNom($nom) == false)
            {
                if(filter_var($email, FILTER_VALIDATE_EMAIL))
                {
                    if($Organisation->verifEmail($email) == false)
                    {
                        if($mdp === $mdp2)
                        {
                            try
                            {
                                $mdp = password_hash($mdp, PASSWORD_BCRYPT);
                                $success = $Inscription->inscriptionOrg($email, $mdp, $nom);
                            } 
                            catch (exception $e) 
                            {
                                $erreurs[] = "Erreur : l'inscription n'a pas pu aboutir.";
                            }

                            if(!$success)
                            {
                                $erreurs[] = "Une erreur inconnue est survenue.";
                            }
                        } 
                        else 
                        {
                            $erreurs[] = "Erreur : Les mots de passe ne sont pas identiques.";
                        }
                    }
                    else 
                    {
                        $erreurs[] = "Erreur : L'Email est indisponible.";
                    }
                } 
                else 
                {
                    $erreurs[] = "Erreur : L'Email n'est pas correct.";
                }
            } 
            else 
            {
                $erreurs[] = "Erreur : Le nom est indisponible.";
            }
        } 
        else 
        {
            $erreurs[] = "Erreur : Tous les champs doivent être remplis.";
        }
    } 
    else 
    {        
        header("location:".ROOT_URL."index.php");
    }
}

$data = array(
    "success" => $success,
    "erreurs" => $erreurs,
);

$data = json_encode($data);

header("location:".VIEWS_URL.$tpl."?data=$data");

?>