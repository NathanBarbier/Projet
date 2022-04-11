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
$page = "controllers/visitor/".$tpl;

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
                                try {
                                    $Organization->setName($name);
                                    $lastInsertedId = $Organization->create();
                                    print($lastInsertedId);
                                    
                                    LogHistory::create(0, 'create', 'organization', $lastInsertedId, $Organization->getName(), null, null, null, $lastInsertedId, "INFO", null, $ip, $page);

                                } catch (\Throwable $th) {
                                    $errors[] = "Erreur : l'inscription n'a pas pu aboutir.";
                                    LogHistory::create(0, 'create', 'organization', null, null, null, null, null, $lastInsertedId, "ERROR", $th->getMessage(), $ip, $page);
                                                                        
                                    header("location:".ROOT_URL."index.php");
                                }
                                try
                                {
                                    $User->setEmail($email);
                                    $User->setPassword($pwd);
                                    $User->setFk_organization($lastInsertedId);
                                    $User->setAdmin(1);
                                    $User->setConsent(1);
                                    $lastInsertedId = $User->create();
                                    LogHistory::create($lastInsertedId, 'signup', 'user', $lastInsertedId, $User->getEmail(), null, null, null, $User->getFk_organization(), "INFO", null, $ip, $page);
                                        
                                    header("location:".CONTROLLERS_URL.'visitor/login.php?msg=inscription&on=1&type=success&title=Succès');
                                } 
                                catch (\Throwable $th) 
                                {
                                    $errors[] = "Erreur : l'inscription n'a pas pu aboutir.";
                                    LogHistory::create(0, 'signup', 'user', null, null, null, null, null, $User->getFk_organization(), "ERROR", $th->getMessage(), $ip, $page);

                                    exit;
                                    header("location:".ROOT_URL."index.php");
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