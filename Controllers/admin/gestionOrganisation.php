<?php
//import all models
require_once "../../traitements/header.php";

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

    $tpl = "gestionOrganisation.php";
    $data = new stdClass;

    $success = false;
    $errors = array();

    $CurrentOrganization = new stdClass;

    $CurrentOrganization->name = $Organization->getName();
    $CurrentOrganization->email = $Organization->getEmail();
    $CurrentOrganization->membersCount = $Organization->countUsers();
    $CurrentOrganization->projectsCount = $Organization->countProjects();


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
                        $errors[] = "Erreur : Le mot de passe doit contenir entre 8 et 100 caractères, au moins un caractère spécial, une minuscule, une majuscule, un chiffre et ne doit pas contenir d'espace.";
                    } 
                    else
                    {
                        $newPwd = password_hash($newPwd, PASSWORD_BCRYPT);
                        $oldPwdbdd = $Organization->getPassword();
    
                        if(!password_verify($oldPwd, $oldPwdbdd))
                        {
                            $errors[] = "L'ancien mot de passe est incorrect.";
                        } 
                        else 
                        {
                            if($oldPwd == $newPwd)
                            {
                                $errors[] = "Erreur : Le mot de passe ne peut pas être le même qu'avant.";
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
                    $errors[] = "Erreur : Les deux nouveaux mots de passes ne sont pas identiques.";
                }
            } 
            else
            {
                $errors[] = "Erreur : Un champs n'est pas rempli.";
            }
    
        } 
        else 
        {
            header("location:".ROOT_PATH."index.php");
        }
    }


    require_once VIEWS_PATH."admin".DIRECTORY_SEPARATOR.$tpl;
}
else
{
    header("location:".ROOT_URL."index.php");
}


?>