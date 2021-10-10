<?php
//import all models
require_once "../../traitements/header.php";

$idUser = $_SESSION["idUtilisateur"] ?? null;
$rights = $_SESSION["habilitation"] ?? false;
$idOrganisation = $_SESSION["idOrganisation"] ?? false;

if($rights === 'user')
{
    $action = GETPOST('action');
    $envoi = GETPOST('envoi');
    
    $firstname = GETPOST('prenom');
    $lastname = GETPOST('nom');
    $email = GETPOST('email');
    $idPosition = GETPOST('idPosition');
    $idTeam = GETPOST('idTeam');
    $birth = GETPOST('birth');
    
    $oldmdp = GETPOST('oldmdp');
    $newmdp = GETPOST('newmdp');
    $newmdp2 = GETPOST('newmdp');
    
    
    $User = new User($idUser);
    $Position = new Position();
    $Team = new Team();
    
    $tpl = "passwordUpdate.php";
    
    $erreurs = array();
    $success = false;
    
    $data = new stdClass;
    
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
                        $erreurs[] = "Erreur : Le mot de passe doit contenir entre 8 et 100 caractères, au moins un caractère spécial, une minuscule, une majuscule, un chiffre et ne doit pas contenir d'espace.";
                    } 
                    else
                    {
                        $newmdp = password_hash($newmdp, PASSWORD_BCRYPT);
                        $oldmdpbdd = $User->getPassword();
    
                        if(!password_verify($oldmdp, $oldmdpbdd))
                        {
                            $erreurs[] = "L'ancien mot de passe est incorrect.";
                        } 
                        else 
                        {
                            if($oldmdp == $newmdp)
                            {
                                $erreurs[] = "Erreur : Le mot de passe ne peut pas être le même qu'avant.";
                            } 
                            else 
                            {
                                $status = $User->updatePassword($newmdp);
    
                                if($status)
                                {
                                    $success = true;
                                }
                                else
                                {
                                    $erreurs[] = "Erreur SQL : Le mot de passe n'a pas pu être changé.";
                                }
                            }
                        }
                    }  
                } 
                else 
                {
                    $erreurs[] = "Erreur : Les deux nouveaux mots de passes ne sont pas identiques.";
                }
            } 
            else
            {
                $erreurs[] = "Erreur : Un champs n'est pas rempli.";
            }
    
        } 
        else 
        {
            header("location:".ROOT_PATH."index.php");
        }
    }

    
    require_once VIEWS_PATH."membres/".$tpl;
}
else
{
    header("location:".ROOT_URL."index.php");
}





?>