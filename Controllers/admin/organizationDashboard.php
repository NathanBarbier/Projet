<?php
//import all models
require_once "../../services/header.php";
require "layouts/head.php";

// For project counters
$ProjectRepository = new ProjectRepository();


$Organization = new Organization();
$Organization->setRowid($idOrganization);
$Organization->fetchName();

$User = new User($idUser);

$action = htmlentities(GETPOST('action'));
$envoi = GETPOST('envoi');
$oldPwd = htmlentities(GETPOST('oldpwd'));
$newPwd = htmlentities(GETPOST('newpwd'));
$newPwd2 = htmlentities(GETPOST('newpwd2'));
$email = htmlentities(GETPOST('email'));
$firstname = htmlentities(GETPOST('firstname'));
$lastname = htmlentities(GETPOST('lastname'));

$tpl = "organizationDashboard.php";
$page = "controllers/admin/".$tpl;

$success = GETPOST('success');
$errors = array();
$invalidInput = array();
$invalidForm = array();

if($action == "deleteOrganization")
{
    try {
        $Organization->delete();
        LogHistory::create($idUser, 'delete', 'organization', $idOrganization, null, null, null, null, $idOrganization, "IMPORTANT", null, $ip, $page);
        header("location:".ROOT_URL."index.php");
        exit;
    } catch (\Throwable $th) {
        $errors[] = "Une erreur est survenue.";
        LogHistory::create($idUser, 'delete', 'organization', $idOrganization, null, null, null, null, $idOrganization, "ERROR", $th->getMessage(), $ip, $page);
    }
}

if($action == 'userUpdate')
{
    if($firstname && $lastname && $email)
    {
        if(filter_var($email, FILTER_VALIDATE_EMAIL))
        {
            try {
                $User->setFirstname($firstname);
                $User->setLastname($lastname);
                $User->setEmail($email);
                $User->update();
                LogHistory::create($idUser, 'update', 'user', $idUser, $User->getFirstname()." ".$User->getLastname(), null, null, null, $idOrganization, "INFO", null, $ip, $page);
                $success = "Vos informations ont bien été mises à jour.";
            } catch (\Throwable $th) {
                $errors[] = "Une error est survenue.";
                LogHistory::create($idUser, 'update', 'user', $idUser, $User->getFirstname()." ".$User->getLastname(), null, null, null, $idOrganization, "ERROR", $th->getMessage(), $ip, $page);
                
            }
        }
        else
        {
            $errors[] = "L'adresse email n'est pas valide.";
        }
    }
}

if($action == "updatePassword")
{
    if($envoi)
    {    
        if($oldPwd && $newPwd && $newPwd2)
        {
            if($newPwd === $newPwd2)
            {
                if (strlen($newPwd) >= 8 && strlen($newPwd) <= 100)
                {
                    if(password_verify($oldPwd, $User->getPassword()))
                    {
                        if($oldPwd != $newPwd)
                        {
                            try {
                                $User->setPassword($newPwd);
                                $User->update();
                                LogHistory::create($idUser, 'update password', 'user', $idUser, null, null, $idOrganization, "INFO", null, $ip, $page);
                                $success = "Le mot de passe a bien été modifié.";
                                $oldPwd = "";
                                $newPwd = "";
                                $newPwd2 = "";
                            } catch (\Throwable $th) {
                                $errors[] = "Une erreur innatendu est survenue. Le mot de passe n'a pas pu être modifié.";
                                LogHistory::create($idUser, 'update password', 'user', $idUser, null, null, $idOrganization, "ERROR", $th->getMessage(), $ip, $page);
                            }
                        }
                        else
                        {
                            $errors[] = "Le mot de passe ne peut pas être le même qu'avant.";
                        }
                    }
                    else
                    {
                        $errors[] = "L'ancien mot de passe est incorrect.";
                    }
                }  
                else
                {
                    $errors[] = "Le mot de passe doit contenir entre 8 et 100 caractères, au moins un caractère spécial, une minuscule, une majuscule, un chiffre et ne doit pas contenir d'espace.";
                }
            } 
            else 
            {
                $errors[] = "Les deux nouveaux mots de passes ne sont pas identiques.";
            }
        } 
        else
        {
            $errors[] = "Un champs n'est pas rempli.";
        }

    } 
    else 
    {
        header("location:".ROOT_PATH."index.php");
        exit;
    }
}

if($action == "updateEmail")
{
    if($email)
    {
        if(filter_var($email, FILTER_VALIDATE_EMAIL))
        {
            try {
                $User->setEmail($email);
                $User->update();
                LogHistory::create($idUser, 'update', 'user', $idUser, $User->getFirstname()." ".$User->getLastname(), null, null, null, $idOrganization, "INFO", null, $ip, $page);
                $success = "L'adresse email a bien été modifiée.";
                $email = '';
            } catch (\Throwable $th) {
                $errors[] = "Une erreur innatendue est survenue.";
                LogHistory::create($idUser, 'update', 'user', $idUser, $User->getFirstname()." ".$User->getLastname(), null, null, null, $idOrganization, "ERROR", $th->getMessage(), $ip, $page);
            }
        }
        else
        {
            $errors[] = "L'adresse email n'est pas correcte.";
        }
    }
    else
    {
        $errors[] = "L'adresse email n'a pas été saisie.";
    }

    if(count($errors) > 0)
    {
        $invalidInput[] = 'email';
        $invalidForm[] = 'email';
    }
}

require_once VIEWS_PATH."admin".DIRECTORY_SEPARATOR.$tpl;
?>