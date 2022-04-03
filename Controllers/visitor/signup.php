<?php
//import all models
require_once "../../services/header.php";
require "layouts/head.php";

$rights = $_SESSION["rights"] ?? false;
$idUser = $_SESSION["idUser"] ?? false;

$action = htmlentities(GETPOST('action'));
$envoi = GETPOST('envoi');
$email = htmlentities(GETPOST('email'));
$name = htmlentities(GETPOST('name'));
$pwd = htmlentities(GETPOST('pwd'));
$pwd2 = htmlentities(GETPOST('pwd2'));
$consent = htmlentities(GETPOST('consent'));

if ($consent == "on")
{
    $consent = true;
}

$Organization = new Organization();
$User = new User();

$errors = array();
$success = false;

$tpl = "signup.php";

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
                    $User->setEmail($email);
                    if($User->checkByEmail() == false)
                    {
                        if($pwd === $pwd2)
                        {
                            $speciaux = "/[.!@#$%^&*()_+=]/";
                            $nombres = "/[0-9]/";
                            if(preg_match($nombres, $pwd) && preg_match($speciaux, $pwd) && strlen($pwd) >= 8 && strlen($pwd) <= 100 && strtolower($pwd) !== $pwd && strtoupper($pwd) !== $pwd)
                            {
                                try
                                {
                                    $fk_organization = intval($Organization->fetch_last_insert_id()) + 1;
                                    $idUser = intval($User->fetch_last_insert_id()) + 1;
    
                                    $User->setEmail($email);
                                    $User->setPassword($pwd);
                                    $User->setFk_organization($fk_organization);
                                    $User->setAdmin(1);
                                    $User->setConsent(1);
                                    $lastInsertedId = $User->create();
                                    LogHistory::create($idOrganization, $idUser, "INFO", 'signup', 'user', $User->getEmail(), null, 'user id : '.$lastInsertedId);
                                } 
                                catch (exception $e) 
                                {
                                    $errors[] = "Erreur : l'inscription n'a pas pu aboutir.";
                                    LogHistory::create($idOrganization, $idUser, "ERROR", 'signup', 'user', $User->getEmail(), null, null, $e->getMessage());
                                }

                                try {
                                    $Organization->setName($name);
                                    $lastInstertedId = $Organization->create();
                                    LogHistory::create($idOrganization, $idUser, "INFO", 'create', 'Organization', $Organization->getName(), null, 'organization id : '.$lastInstertedId);
    
                                    header("location:".CONTROLLERS_URL.'visitor/login.php?msg=inscription&on=1&type=success&title=Succès');
                                    exit;
                                } catch (\Throwable $th) {
                                    $errors[] = "Erreur : l'inscription n'a pas pu aboutir.";
                                    LogHistory::create($idOrganization, $idUser, "ERROR", 'create', 'Organization', $Organization->getName(), null, null, $th->getMessage());
                                }
                            }
                            else
                            {
                                $errors[] = "Le mot de passe doit : contenir entre 8 et 100 caractères, au moins un caractère spécial \"[.!@#$%^&*()_+=]\", un chiffre, une lettre minuscule et une lettre majuscule.";
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


require_once VIEWS_PATH."visitor/".$tpl;

?>