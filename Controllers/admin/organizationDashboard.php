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

$success = GETPOST('success');
$errors = array();
$invalidInput = array();
$invalidForm = array();

if($action == "deleteOrganization")
{
    try {
        $Organization->delete();
        LogHistory::create($idOrganization, $idUser, "IMPORTANT", 'delete', 'organization', $Organization->getName(), null, 'organization id : '.$Organization->getRowid(), null, $ip);
        header("location:".ROOT_URL."index.php");
        exit;
    } catch (\Throwable $th) {
        $errors[] = "Une erreur est survenue.";
        LogHistory::create($idOrganization, $idUser, "ERROR", 'delete', 'organization', $Organization->getName(), null, 'organization id : '.$Organization->getRowid(), $th->getMessage(), $ip);
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
                LogHistory::create($idOrganization, $idUser, "INFO",'update', 'user', $User->getLastname().' '.$User->getFirstname(), null, 'user id : '.$User->getRowid(), null, $ip);
                $success = "Vos informations ont bien été mises à jour.";
            } catch (\Throwable $th) {
                $errors[] = "Une error est survenue.";
                LogHistory::create($idOrganization, $idUser, "ERROR",'update', 'user', $User->getLastname().' '.$User->getFirstname(), null, 'user id : '.$User->getRowid(), $th->getMessage(), $ip);
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
                                LogHistory::create($idOrganization, $idUser, "INFO", 'update password', 'user', $User->getLastname().' '.$User->getFirstname(), null, 'user id : '.$User->getRowid(), null, $ip);
                                $success = "Le mot de passe a bien été modifié.";
                                $oldPwd = "";
                                $newPwd = "";
                                $newPwd2 = "";
                            } catch (\Throwable $th) {
                                $errors[] = "Une erreur innatendu est survenue. Le mot de passe n'a pas pu être modifié.";
                                LogHistory::create($idOrganization, $idUser, "ERROR", 'update password', 'user', $User->getLastname().' '.$User->getFirstname(), null, 'user id : '.$User->getRowid(), $th->getMessage(), $ip);
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
                LogHistory::create($idOrganization, $idUser, "INFO",'update email', 'user', $User->getLastname().' '.$User->getFirstname(), $User->getEmail(), 'user id : '.$User->getRowid(), null, $ip);
                $success = "L'adresse email a bien été modifiée.";
                $email = '';
            } catch (\Throwable $th) {
                $errors[] = "Une erreur innatendue est survenue.";
                LogHistory::create($idOrganization, $idUser, "ERROR",'update email', 'user', $User->getLastname().' '.$User->getFirstname(), $User->getEmail(), 'user id : '.$User->getRowid(), $th->getMessage(), $ip);
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