<?php
//import all models
require_once "../../services/header.php";

$idOrganization = $_SESSION["idOrganization"] ?? false;
$rights = $_SESSION["rights"] ?? false;

if($rights === "admin")
{
    $Organization = new Organization($idOrganization);

    $action = GETPOST('action');
    $envoi = GETPOST('envoi');
    $oldPwd = GETPOST('oldpwd');
    $newPwd = GETPOST('newpwd');
    $newPwd2 = GETPOST('newpwd2');
    $email = GETPOST('email');

    $tpl = "gestionOrganisation.php";
    $data = new stdClass;

    $success = false;
    $errors = array();
    $invalidInput = array();
    $invalidForm = array();

    if($action == "deleteOrganization")
    {
        $Organization->delete();
        header("location:".ROOT_URL."index.php");
    }

    if($action == "updatePassword")
    {
        if($envoi)
        {    
            if($oldPwd && $newPwd && $newPwd2)
            {
                if($newPwd === $newPwd2)
                {
                    if (strlen($newPwd) < 8 || strlen($newPwd) > 100)
                    {
                        $errors[] = "Le mot de passe doit contenir entre 8 et 100 caractères, au moins un caractère spécial, une minuscule, une majuscule, un chiffre et ne doit pas contenir d'espace.";
                    } 
                    else
                    {
                        $newPwdStock = $newPwd;
                        $newPwd = password_hash($newPwd, PASSWORD_BCRYPT);
                        $oldPwdbdd = $Organization->getPassword();
    
                        if(!password_verify($oldPwd, $oldPwdbdd))
                        {
                            $errors[] = "L'ancien mot de passe est incorrect.";
                        } 
                        else 
                        {
                            if($oldPwd == $newPwdStock)
                            {
                                $errors[] = "Le mot de passe ne peut pas être le même qu'avant.";
                            } 
                            else 
                            {
                                $status = $Organization->updatePassword($newPwd);
    
                                if($status)
                                {
                                    $success = "Le mot de passe a bien été modifié.";

                                    $oldPwd = "";
                                    $newPwd = "";
                                    $newPwd2 = "";
                                }
                                else
                                {
                                    $errors[] = "Une erreur innatendu est survenue. Le mot de passe n'a pas pu être modifié.";
                                }
                            }
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

    if($action == "updateEmail")
    {
        if($email)
        {
            if(filter_var($email, FILTER_VALIDATE_EMAIL))
            {
                $status = $Organization->updateEmail($email);

                if($status)
                {
                    $success = "L'adresse email a bien été modifiée.";
                    $email = '';
                    $CurrentOrganization->email = $Organization->getEmail();
                }
                else
                {
                    $errors[] = "Une erreur innatendue est survenue.";
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
}
else
{
    header("location:".ROOT_URL."index.php");
}


?>