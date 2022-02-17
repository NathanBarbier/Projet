<?php
//import all models
require_once "../../services/header.php";
require "layouts/head.php";

$action = htmlentities(GETPOST('action'));
//override $idUser
$idUser = intval(GETPOST('idUser'));
$envoi = GETPOST('envoi');

$firstname = htmlentities(GETPOST('firstname'));
$lastname = htmlentities(GETPOST('lastname'));
$email = htmlentities(GETPOST('email'));
$birth = htmlentities(GETPOST('birth'));

$oldmdp = htmlentities(GETPOST('oldmdp'));
$newmdp = htmlentities(GETPOST('newmdp'));
$newmdp2 = htmlentities(GETPOST('newmdp2'));

$Organization = new Organization($idOrganization);

$errors = array();
$success = false;

$tpl = "listeMembres.php";

if($action == "updateFirstname")
{
    if($idUser && $firstname)
    {
        if($envoi)
        {
            $User = $Organization->fetchUser($idUser);
            // check if the user belongs to the Organization
            if($User)
            {
                if($firstname != $User->getFirstname())
                {
                    try
                    {
                        $oldFirstname = $User->getFirstname();
                        $User->setFirstname($firstname);
                        $User->update();
                        LogHistory::create($idOrganization, $idUser, "INFO", 'update firstname', 'user', $User->getLastname().' '.$oldFirstname, $User->getFirstname(), 'user id : '.$User->getRowid());
                        $success = "Le prénom a bien été modifié.";
                    } 
                    catch (exception $e)
                    {
                        $errors[] = "Le prénom n'a pas pu être modifié.";
                        LogHistory::create($idOrganization, $idUser, "ERROR", 'update firstname', 'user', $User->getLastname().' '.$oldFirstname, $User->getFirstname(), 'user id : '.$User->getRowid(), $th);
                    }
                } 
                else 
                {
                    $errors[] = "Le nom est le même qu'avant.";
                }
            }
        } 
        else 
        {
            header("location:".ROOT_URL."/index.php");
        }
    } 
    else 
    {
        header("location:".ROOT_URL."/index.php");
    }
}

if($action == "updateLastname")
{
    if($idUser && $lastname)
    {
        if($envoi)
        {
            $User = $Organization->fetchUser($idUser);
            if($User)
            {
                if($lastname != $User->getLastname())
                {
                    try
                    {
                        $oldLastname = $User->getLastname();
                        $User->setLastname($lastname);
                        $User->update();
                        LogHistory::create($idOrganization, $idUser, "INFO", 'update lastname', 'user', $oldLastname.' '.$User->getFirstname(), $User->getLastname(), 'user id : '.$User->getRowid());
                        $success = "Le nom a bien été modifié.";
                    } 
                    catch (exception $e)
                    {
                        $errors[] = "La modification de nom n'a pas pu aboutir.";
                        LogHistory::create($idOrganization, $idUser, "ERROR", 'update lastname', 'user', $oldLastname.' '.$User->getFirstname(), $User->getLastname(), 'user id : '.$User->getRowid(), $th);
                    }
                } 
                else 
                {
                    $errors[] = "Le nom n'a pas été modifié.";
                }
            }
        } 
        else
        {
            header("location:".ROOT_URL."index.php");
        }
    }
    else
    {
        header("location:".ROOT_URL."index.php");
    }
}

if($action == "deleteUser")
{
    if($idUser)
    {
        $User = $Organization->fetchUser($idUser);
        if($User)
        {
            try {
                $Organization->removeUser($idUser);
                $User->delete();
                LogHistory::create($idOrganization, $idUser, "WARNING", 'delete', 'user', $User->getLastname().' '.$User->getFirstname(), '', 'user id : '.$User->getRowid());
                $success = "La suppression d'utilisateur a bien été effectuée.";
            } catch (\Throwable $th) {
                $errors[] = "La suppression d'utilisateur n'a pas pu aboutir.";
                LogHistory::create($idOrganization, $idUser, "ERROR", 'delete', 'user', $User->getLastname().' '.$User->getFirstname(), '', 'user id : '.$User->getRowid(), $th);
            }
        }
    } 
    else
    {
        header("location:".ROOT_PATH."index.php");
    }
}

require_once VIEWS_PATH."admin/".$tpl;
