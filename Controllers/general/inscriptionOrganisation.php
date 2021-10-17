<?php
//import all models
require_once "../../traitements/header.php";

$rights = $_SESSION["rights"] ?? false;
$idUser = $_SESSION["idUser"] ?? false;

$action = GETPOST('action');
$envoi = GETPOST('envoi');
$email = GETPOST('email');
$name = GETPOST('name');
$pwd = GETPOST('pwd');
$pwd2 = GETPOST('pwd2');
$consent = GETPOST('consent');

$Organization = new Organization();
$Inscription = new Inscription();

$errors = array();
$success = false;

$tpl = "inscriptionOrganisation.php";


if($action == "inscriptionOrg")
{
    if($envoi) 
    {
        if($name && $email && $pwd && $pwd2 && $consent)
        {
            if($Organization->checkByName($name) == false)
            {
                if(filter_var($email, FILTER_VALIDATE_EMAIL))
                {
                    if($Organization->checkByEmail($email) == false)
                    {
                        if($pwd === $pwd2)
                        {
                            try
                            {
                                $pwd = password_hash($pwd, PASSWORD_BCRYPT);
                                $success = $Inscription->inscriptionOrg($email, $pwd, $name, $consent);
                            } 
                            catch (exception $e) 
                            {
                                $errors[] = "Erreur : l'inscription n'a pas pu aboutir.";
                            }

                            if($success)
                            {
                                $message = "L'inscription a bien été prise en compte";
                                header("location:".CONTROLLERS_URL.'general/connexion.php?message='.$message);
                                exit;
                            }
                            else
                            {
                                $errors[] = "Une erreur inconnue est survenue.";
                            }
                        } 
                        else 
                        {
                            $errors[] = "Erreur : Les mots de passe ne sont pas identiques.";
                        }
                    }
                    else 
                    {
                        $errors[] = "Erreur : L'Email est indisponible.";
                    }
                } 
                else 
                {
                    $errors[] = "Erreur : L'Email n'est pas correct.";
                }
            } 
            else 
            {
                $errors[] = "Erreur : Le nom est indisponible.";
            }
        } 
        else 
        {
            $errors[] = "Erreur : Tous les champs doivent être remplis.";
        }
    } 
    else 
    {        
        header("location:".ROOT_URL."index.php");
    }
}


require_once VIEWS_PATH."general/".$tpl;

?>