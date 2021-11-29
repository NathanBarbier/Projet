<?php
//import all models
require_once "../../services/header.php";

// Import PHPMailer classes into the global namespace 
use PHPMailer\PHPMailer\PHPMailer; 
use PHPMailer\PHPMailer\Exception; 
 
require_once PHP_MAILER_PATH.'Exception.php'; 
require_once PHP_MAILER_PATH.'PHPMailer.php'; 
require_once PHP_MAILER_PATH.'SMTP.php';

$mail = new PHPMailer; 
 
$mail->isSMTP();                      // Set mailer to use SMTP 
$mail->Host = 'smtp.gmail.com';       // Specify main and backup SMTP servers 
$mail->SMTPAuth = true;               // Enable SMTP authentication 
$mail->Username = 'storiesHelperSignUp@gmail.com';   // SMTP username 
$mail->Password = 'unsecurepassword';   // SMTP password 
$mail->SMTPSecure = 'tls';            // Enable TLS encryption, `ssl` also accepted 
$mail->Port = 587;                    // TCP port to connect to 

// Sender info 
$mail->setFrom('storiesHelperSignUp@gmail.com', 'storiesHelper'); 
$mail->addReplyTo('storiesHelperSignUp@gmail.com', 'storiesHelper'); 


$rights = $_SESSION["rights"] ?? false;
$idOrganization = $_SESSION["idOrganization"] ?? false;

// var_dump($rights);

if($rights === "admin")
{
    $action = GETPOST('action');
    $idUser = GETPOST('idUser');
    $envoi = GETPOST('envoi');
    $firstname = GETPOST('firstname');
    $lastname = GETPOST('lastname');
    $email = GETPOST('email');
    $idPosition = GETPOST('idPosition');
    $birth = GETPOST('birth');
    $oldpwd = GETPOST('oldpassword');
    $newpwd = GETPOST('newpassword');
    $newpwd = GETPOST('newpassword2');

    $User = new User($idUser);
    $Position = new Position();
    $Team = new Team();

    $positions = $Position->fetchAll($idOrganization);
    $teams = $Team->fetchAll($idOrganization);

    $errors = array();
    $success = false;

    // $data = new stdClass;

    $tpl = "inscriptionUtilisateur.php";

    
    // exit;
    
    if($action == "signup")
    {
        if($envoi)
        {
            if($email && $firstname && $lastname && $birth && $idPosition)
            {
                if(filter_var($email, FILTER_VALIDATE_EMAIL))
                {
                    $idPosition = intval($idPosition);
                    if(is_int($idPosition))
                    {
                        $speciaux = "/[.!@#$%^&*()_+=]/";
                        $nombres = "/[0-9]/";
                        if(!preg_match($nombres, $firstname) && !preg_match($speciaux, $firstname))
                        {
                            if(!preg_match($nombres, $lastname) && !preg_match($speciaux, $lastname))
                            {
                                if(!$User->checkByEmail($email))
                                {
                                    $temporaryPassword = generateRandomString(6);
                                    $password = password_hash($temporaryPassword, PASSWORD_BCRYPT);
                                    
                                    if($User->create($firstname, $lastname, $birth, $idPosition, $email, $idOrganization, $password))
                                    {
                                        $organization = new organization($idOrganization);
                                        $organizationName = $organization->getName();

                                        $subject = "New registration !";

                                        $mailText = "You have been registered by $organizationName on StoriesHelper <br>";
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
                                    }
                                    else 
                                    {
                                        $errors[] = "Erreur : L'inscription n'a pas pu aboutir.";
                                    }
                                } 
                                else 
                                {
                                    $errors[] = "Erreur : L'adresse email est déjà prise.";
                                }
                            } 
                            else 
                            {
                                $errors[] = "errors : Le nom n'est pas correct.";
                            }
                        } 
                        else 
                        {
                            $errors[] = "Erreur : Le prénom n'est pas correct.";
                        }
                    } 
                    else 
                    {
                        $errors[] = "Le poste n'est pas correct.";
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
}
else
{
    header("location:".ROOT_URL."index.php");
}


?>