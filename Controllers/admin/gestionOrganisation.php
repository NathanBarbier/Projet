<?php
//import all models
require_once "../../services/header.php";

$idOrganization = $_SESSION["idOrganization"] ?? false;
$idUser = $_SESSION["idUser"] ?? false;
$rights = $_SESSION["rights"] ?? false;

if($rights === "admin")
{
    $Organization = new Organization($idOrganization);
    $User = new User($idUser);

    $action = htmlentities(GETPOST('action'));
    $envoi = GETPOST('envoi');
    $oldPwd = htmlentities(GETPOST('oldpwd'));
    $newPwd = htmlentities(GETPOST('newpwd'));
    $newPwd2 = htmlentities(GETPOST('newpwd2'));
    $email = htmlentities(GETPOST('email'));
    $firstname = htmlentities(GETPOST('firstname'));
    $lastname = htmlentities(GETPOST('lastname'));

    $tpl = "gestionOrganisation.php";

    $success = GETPOST('success');
    $errors = array();
    $invalidInput = array();
    $invalidForm = array();

    if($action == "deleteOrganization")
    {
        try {
            $Organization->delete();
            LogHistory::create($idUser, 'delete', 'organization', $Organization->getName());
            header("location:".ROOT_URL."index.php");
            exit;
        } catch (\Throwable $th) {
            //throw $th;
            $errors[] = "Une erreur est survenue.";
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
                    LogHistory::create($idUser, 'update', 'user', $User->getLastname().' '.$User->getFirstname());
                    $success = "Vos informations ont bien été mises à jour.";
                } catch (\Throwable $th) {
                    //throw $th;
                    $errors[] = "Une error est survenue.";
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
                                    LogHistory::create($idUser, 'update password', 'user', $User->getLastname().' '.$User->getFirstname());
                                    $success = "Le mot de passe a bien été modifié.";
                                    $oldPwd = "";
                                    $newPwd = "";
                                    $newPwd2 = "";
                                } catch (\Throwable $th) {
                                    //throw $th;
                                    $errors[] = "Une erreur innatendu est survenue. Le mot de passe n'a pas pu être modifié.";
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
                    LogHistory::create($idUser, 'update email', 'user', $User->getLastname().' '.$User->getFirstname(), $User->getEmail());
                    $success = "L'adresse email a bien été modifiée.";
                    $email = '';
                } catch (\Throwable $th) {
                    //throw $th;
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