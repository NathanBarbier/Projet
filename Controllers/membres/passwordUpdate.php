<?php
//import all models
require_once "../traitements/header.php";

$action = $_GET["action"] ?? false;
$idUser = $_GET["idUser"] ?? false;

$envoi = $_POST["envoi"] ?? false;

$firstname = $_POST["prenom"] ?? false;
$lastname = $_POST["nom"] ?? false;
$email = $_POST["email"] ?? false;
$idPoste = $_POST["idPoste"] ?? false;
$idEquipe = $_POST["idEquipe"] ?? false;
$birth = $_POST["birth"] ?? false;

$oldmdp = $_POST["oldmdp"] ?? false;
$newmdp = $_POST["newmdp"] ?? false;
$newmdp2 = $_POST["newmdp2"] ?? false;

$rights = $_SESSION["habilitation"] ?? false;
$idOrganisation = $_SESSION["idOrganisation"] ?? false;

$User = new User($idUser);
$Poste = new Poste();
$Equipe = new Equipe();


if($action == "updatePassword")
{
    if($envoi)
    {
        if(!empty($oldmdp) && !empty($newmdp) && !empty($newmdp2))
        {
            if($newmdp === $newmdp2)
            {
                if (strlen($newmdp) < 8 || strlen($newmdp) > 100)
                {
                    $erreur = "Erreur : Le mot de passe doit contenir entre 8 et 100 caractères, au moins un caractère spécial, une minuscule, une majuscule, un chiffre et ne doit pas contenir d'espace.";
                } 
                else
                {
                    $oldmdp = $User->getPassword();
                    if(!password_verify($oldmdp, hash($newmdp, PASSWORD_BCRYPT)))
                    {
                        $erreur = "L'ancien mot de passe est incorrect.";
                    } 
                    else 
                    {
                        if($oldmdp == hash($newmdp, PASSWORD_BCRYPT))
                        {
                            $erreur = "Erreur : Le mot de passe ne peut pas être le même qu'avant.";
                        } 
                        else 
                        {
                            try
                            {
                                $User->updatePassword(hash($newmdp, PASSWORD_BCRYPT));
                            } 
                            catch (Exception $e) 
                            {
                                $erreur = "Erreur SQL : Le mot de passe n'a pas pu être changé.";
                            }
                            $success = true;
                        }
                    }
                }  
            } 
            else 
            {
                $erreur = "Erreur : Les deux nouveaux mots de passes ne sont pas identiques.";
            }
        } 
        else
        {
            $erreur = "Erreur : Un champs n'est pas rempli.";
        }

    } 
    else 
    {
        header("location:".ROOT_PATH."index.php");
    }
}



?>