<?php
require_once "../../services/header.php";

if( isset( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && ( $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' ) )
{
    // get the user ip adress
    $ip = $_SERVER['REMOTE_ADDR'];

    $envoi = GETPOST('envoi');
    $email = GETPOST('email');
    $password = GETPOST('password');
    $message = GETPOST('message');
    $rememberMe = GETPOST('rememberMe');

    $User = new User();
    $Organization = new Organization();

    $error     = false;
    $success    = false;
    $rights     = false;

    $tpl = "login.php";
    $page = CONTROLLERS_URL."visitor/".$tpl;

    if($envoi)
    {
        if($email && $password)
        {
            if(filter_var($email, FILTER_VALIDATE_EMAIL))
            {
                $User->setEmail($email);
                if($User->checkByEmail())
                {
                    $User->fetchByEmail($email);
                    
                    if(password_verify($password, $User->getPassword()))
                    {
                        // get the user ip adress
                        $userIp = $_SERVER['REMOTE_ADDR'];

                        // check the ip adresse is banned
                        $BannedIp = new BannedIp($userIp);

                        // if the $BannedIp has been fetched
                        if($BannedIp->getRowid() == null) 
                        {
                            $allowed = false;
                            // if user is admin then check if his ip address is allowed
                            if($User->isAdmin())
                            {
                                $AllowedIp = new AllowedIp($userIp, $User->getRowid());

                                // If the $AllowedIp has been fetched
                                if($AllowedIp->getFk_user() != null && $User->getRowid() == $AllowedIp->getFk_user())
                                {
                                    $allowed = true;
                                }
                            
                            }


                            if(($User->isAdmin() && $allowed) || !$User->isAdmin())
                            {
                                try 
                                {
                                    $_SESSION["idUser"] = $User->getRowid();
                                    $_SESSION["idOrganization"] = $User->getFk_organization();
            
                                    $token = '';
                                    if($rememberMe)
                                    {
                                        $token = bin2hex(random_bytes(15));
                                        setcookie(
                                            'remember_me',
                                            $User->getRowid() . "-" . $token,
                                            time() + 604800,
                                            '',
                                            '',
                                            false, //true on production otherwise false
                                            true
                                        );
                                    }
            
                                    $User->setToken($token);
                                    $User->updateToken();
            
                                    $consent = $User->getConsent();
                                    if($consent == 1)
                                    {
                                        $_SESSION["rights"] = $User->isAdmin() == 1 ? "admin" : "user";
                                        LogHistory::create($User->getRowid(), 'connect', 'user', $User->getRowid(), null, null, $User->getFk_organization(), "INFO", null, $ip, $page);
                                    }
                                    else
                                    {
                                        $_SESSION["rights"] = "needConsent";
                                        LogHistory::create($User->getRowid(), 'connect', 'user', $User->getRowid(), null, null, $User->getFk_organization(), "INFO", null, $ip, $page);
                                    }
    
                                    $rights = $_SESSION['rights'] ?? false;
                                    
                                    $success = 'Vous êtes connecté.';
                                } 
                                catch (Exception $e) 
                                {
                                    // $error = $e->getMessage();
                                    // echo json_encode($th);
                                    LogHistory::create($User->getRowid(), 'connect', 'user', $User->getRowid(), null, $User->getFk_organization(), "ERROR", $e->getMessage(), $ip, $page);
                                    $error = "Une erreur est survenue.";
                                }
                            }
                            else
                            {
                                $error = "Votre adresse ip n'est pas autorisée à accéder à l'interface administrateur.";
                            }
                        }
                        else
                        {
                            $error = "L'adresse ip est bannie.";
                        }      
                    } 
                    else 
                    {
                        $error = "La paire identifiant / mot de passe est incorrecte.";
                    }
                }
                else
                {
                    $error = "La paire identifiant / mot de passe est incorrecte.";
                }
            } 
            else 
            {
                $error = "Le format de l'adresse email est incorrect.";
            }
        } 
        else 
        {
            $error = "Un champs n'a pas été rempli.";
        }

    }

    $response = array(
        'error'     => $error,
        'success'   => $success,
        'rights'    => $rights
    );

    echo json_encode($response);
}

?>