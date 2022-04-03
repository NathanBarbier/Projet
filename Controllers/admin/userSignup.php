<?php
//import all models
require_once "../../services/header.php";
require "layouts/head.php";

require_once PHP_MAILER_PATH.'Exception.php'; 
require_once PHP_MAILER_PATH.'PHPMailer.php'; 
require_once PHP_MAILER_PATH.'SMTP.php';

// Import PHPMailer classes into the global namespace 
use PHPMailer\PHPMailer\PHPMailer; 
use PHPMailer\PHPMailer\Exception; 

$mail = new PHPMailer; 

$mail->isSMTP();                      // Set mailer to use SMTP 
$mail->Host = 'smtp.gmail.com';       // Specify main and backup SMTP servers 
$mail->SMTPAuth = true;               // Enable SMTP authentication 
$mail->Username = 'storiesHelperSignUp@gmail.com';   // SMTP username 
$mail->Password = '248757C537DF1E2E20663659518A8C8BD6BA9E753BA25FEC108D9A7E9925140A';   // SMTP password 
$mail->SMTPSecure = 'tls';            // Enable TLS encryption, `ssl` also accepted 
$mail->Port = 587;                    // TCP port to connect to 

// Sender info 
$mail->setFrom('storiesHelperSignUp@gmail.com', 'storiesHelper'); 
$mail->addReplyTo('storiesHelperSignUp@gmail.com', 'storiesHelper'); 

$action = htmlentities(GETPOST('action'));
$idUser = intval(GETPOST('idUser'));
$envoi = GETPOST('envoi');
$firstname = htmlentities(GETPOST('firstname'));
$lastname = htmlentities(GETPOST('lastname'));
$email = htmlentities(GETPOST('email'));
$birth = htmlentities(GETPOST('birth'));
$oldpwd = htmlentities(GETPOST('oldpassword'));
$newpwd = htmlentities(GETPOST('newpassword'));
$newpwd = htmlentities(GETPOST('newpassword2'));

$User = new User();

$errors = array();
$success = false;

$tpl = "userSignup.php";

if($action == "signup")
{
    if($envoi)
    {
        if($email && $firstname && $lastname && $birth)
        {
            if(filter_var($email, FILTER_VALIDATE_EMAIL))
            {
                $speciaux = "/[.!@#$%^&*()_+=]/";
                $nombres = "/[0-9]/";
                if(!preg_match($nombres, $firstname) && !preg_match($speciaux, $firstname))
                {
                    if(!preg_match($nombres, $lastname) && !preg_match($speciaux, $lastname))
                    {
                        if(!$User->checkByEmail($email))
                        {
                            try {
                                $temporaryPassword = generateRandomString(6);
                            
                                $User->setEmail($email);
                                $User->setFk_organization($idOrganization);
                                $User->setPassword($temporaryPassword);
                                $User->setAdmin(0);
                                $User->setFirstname($firstname);
                                $User->setLastname($lastname);
                                $User->setBirth($birth);
                                $lastInsertedId = $User->create();
                                LogHistory::create($idOrganization, $idUser, "INFO", 'signup', 'user', $User->getLastname().' '.$User->getFirstname(), null, 'user id : '.$lastInsertedId, null, $ip);
                            } catch (\Throwable $th) {
                                $errors[] = "L'inscription n'a pas pu aboutir.";
                                LogHistory::create($idOrganization, $idUser, "ERROR", 'signup', 'user', $User->getLastname().' '.$User->getFirstname(), null, null, $th->getMessage(), $ip);
                            }
                            
                            try {
                                $Organization = new Organization($idOrganization);

                                $subject = "New registration !";

                                $mailText = "You have been registered by ".$Organization->getName()." on StoriesHelper <br>";
                                $mailText .= "Your temporary password is ' $temporaryPassword ' , please change it as soon as possible.<br>";
                                $mailText .= "Sincerely, <br> StoriesHelper.";

                                // Add a recipient 
                                $mail->addAddress($email); 
                                
                                // Set email format to HTML 
                                $mail->isHTML(true); 
                                
                                // Mail subject 
                                $mail->Subject = $subject; 
                                
                                // Mail body content  
                                $mail->Body    = $mailText; 
                                
                                // Send email 
                                if(!$mail->send()) { 
                                    $errors[] = 'Message could not be sent. Mailer Error: '.$mail->ErrorInfo; 
                                } else { 
                                    // $success = "Le collaborateur a bien été inscrit."; 
                                    // $success .= " Un email contenant son mot de passe temporaire lui a été envoyé.";    
                                }

                                $success = "Le collaborateur a bien été inscrit et reçu son mot de passe par email."; 
                            } catch (\Throwable $th) {
                                $errors[] = "L'email de confirmation d'inscription n'a pas pu être envoyé.";
                                LogHistory::create($idOrganization, $idUser, "ERROR", 'signup', 'user', $User->getLastname().' '.$User->getFirstname(), null, null, $th->getMessage(), $ip);
                            }
                        } 
                        else 
                        {
                            $errors[] = "L'adresse email est déjà prise.";
                        }
                    } 
                    else 
                    {
                        $errors[] = "Le nom n'est pas correct.";
                    }
                } 
                else 
                {
                    $errors[] = "Le prénom n'est pas correct.";
                }
            } 
            else 
            {
                $errors[] = "Le format de l'adresse email n'est pas correct.";
            }
        } 
        else 
        {
            $errors[] = "Un champs n'est pas rempli.";
        }
    } 
}

require_once VIEWS_PATH."admin/".$tpl;
?>