<?php
//import all models
require_once "../../traitements/header.php";

$rights = $_SESSION["rights"] ?? false;
$idUser = $_SESSION["idUser"] ?? false;

$envoi = GETPOST('envoi');

$email = GETPOST('email');
$password = GETPOST('password');
$message = GETPOST('message');

$User = new User();
$Organization = new Organization();

$errors = array();
$success = false;

$data = array();

$tpl = "connexion.php";

if($envoi)
{
    if($email && $password)
    {
        if(filter_var($email, FILTER_VALIDATE_EMAIL))
        {
            if($User->checkByEmail($email) == true)
            {
                $user = $User->fetchByEmail($email);

                if(password_verify($password, $user->password))
                {
                    $consent = $user->consent;
                    
                    $_SESSION["idUser"] = intval($user->rowid);
                    $_SESSION["idOrganization"] = intval($user->fk_organization);
                    
                    if($consent == true)
                    {
                        $_SESSION["rights"] = "user";
                    }
                    else
                    {
                        $_SESSION["rights"] = "needConsent";
                    }
                    
                    $success = true;
                } 
                else 
                {
                    $errors[] = "Le mot de passe est incorrect.";
                }
            } 
            
            if(!$success)
            {
                if($Organization->checkByEmail($email) == true) 
                {
                    $user = $Organization->fetchByEmail($email);
                    if(password_verify($password, $user->password))
                    {
                        $_SESSION["rights"] = "admin";
                        $_SESSION["idOrganization"] = $user->rowid;
                        $_SESSION["email"] = $user->email;
                        
                        $success = true;
                    } 
                    else 
                    {
                        $errors[] = "Le mot de passe est incorrect.";
                    }
                }
            }

            if(!$success)
            {
                if( (!$User->checkByEmail($email)) && !$Organization->checkByEmail($email) )
                {
                    $errors[] = "Cette adresse email n'est associée à aucun compte.";
                }
            }
        } 
        else 
        {
            $errors[] = "Le format de l'adresse email est incorrect.";
        }
    } 
    else 
    {
        $errors[] = "Un champs n'a pas été rempli.";
    }

} 



if($success)
{
    header("location:".ROOT_URL."index.php");
}
else
{
    require_once VIEWS_PATH."general/".$tpl;
}