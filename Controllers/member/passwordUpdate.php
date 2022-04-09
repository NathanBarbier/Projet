<?php
//import all models
require_once "../../services/header.php";
require "layouts/head.php";

$action = htmlentities(GETPOST('action'));
$envoi = GETPOST('envoi');

$firstname = htmlentities(GETPOST('prenom'));
$lastname = htmlentities(GETPOST('nom'));
$email = htmlentities(GETPOST('email'));
$idTeam = intval(GETPOST('idTeam'));
$birth = htmlentities(GETPOST('birth'));

$oldmdp = htmlentities(GETPOST('oldmdp'));
$newmdp = htmlentities(GETPOST('newmdp'));
$newmdp2 = htmlentities(GETPOST('newmdp2'));
    
$User = new User($idUser);

$tpl = "passwordUpdate.php";
$page = CONTROLLERS_URL."member/".$tpl;

$errors = array();
$success = false;

if($action == "passwordUpdate")
{
    if($envoi)
    {    
        if($oldmdp && $newmdp && $newmdp2)
        {
            if($newmdp === $newmdp2)
            {
                if (strlen($newmdp) < 8 || strlen($newmdp) > 100)
                {
                    $errors[] = "Le mot de passe doit contenir entre 8 et 100 caractères, au moins un caractère spécial, une minuscule, une majuscule, un chiffre et ne doit pas contenir d'espace.";
                } 
                else
                {
                    if(password_verify($oldmdp, $User->getPassword()))
                    {
                        if($oldmdp != $newmdp)
                        {
                            try {
                                $User->setPassword($newmdp);
                                $User->update();
                                LogHistory::create($idUser, 'update password', 'user', $idUser, $User->getFirstname()." ".$User->getLastname(), null, null, null, $idOrganization, "INFO", null, $ip, $page);
                                $success = "Le mot de passe a bien été modifié.";
                                header("location:".CONTROLLERS_URL."member/dashboard.php?success=".$success);
                                exit;
                            } catch (\Throwable $th) {
                                $errors[] = "Une erreur innatendue est survenue.";
                                LogHistory::create($idUser, 'update password', 'user', $idUser, $User->getFirstname()." ".$User->getLastname(), null, null, null, $idOrganization, "ERROR", $th->getMessage(), $ip, $page);
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
    }
}

require_once VIEWS_PATH."member/".$tpl;
?>