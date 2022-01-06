<?php
//import all models
require_once "../../services/header.php";

$rights = $_SESSION["rights"] ?? false;
$idUser = $_SESSION["idUser"] ?? false;

$envoi = GETPOST('envoi');

$email = GETPOST('email');
$password = GETPOST('password');
$message = GETPOST('message');
$rememberMe = GETPOST('rememberMe');

$User = new User();
$Organization = new Organization();

$errors = array();
$success = false;

$data = array();

$tpl = "connexion.php";

if(isset($_COOKIE["remember_me"]))
{
    $cookie = explode("-", $_COOKIE["remember_me"]);
    print_r($cookie);
    if($Organization->checkToken($cookie[0], $cookie[1]))
    {
        $user = $Organization->fetchById($cookie[0]);

        $_SESSION["rights"] = "admin";
        $_SESSION["idOrganization"] = $user->rowid;
        $_SESSION["email"] = $user->email;

        $success = true;
    }

    if(!$success)
    {
        if($User->checkToken($cookie[0], $cookie[1]))
        {
            $user = $Organization->fetchById($cookie[0]);
    
            $_SESSION["rights"] = "user";
            $_SESSION["idUser"] = intval($user->rowid);
            $_SESSION["idOrganization"] = intval($user->fk_organization);
    
            $success = true;
        }
    }
}

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

                    if($rememberMe)
                    {
                        $token = bin2hex(random_bytes(15));
                        setcookie(
                            $user->rowid,
                            $token,
                            time() + 604800,
                        );
                    }

                    $User->addCookie($user->rowid, $token);
                    
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

                        if($rememberMe)
                        {
                            $token = bin2hex(random_bytes(15));
                            setcookie(
                                'remember_me',
                                $user->rowid . "-" . $token,
                                time() + 604800,
                            );
                        }
    
                        $Organization->addCookie($user->rowid, $token);

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
    exit;
}
else
{
    require_once VIEWS_PATH."general/".$tpl;
}