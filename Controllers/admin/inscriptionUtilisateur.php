<?php
//import all models
require_once "../../traitements/header.php";

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


$rights = $_SESSION["habilitation"] ?? false;
$idOrganisation = $_SESSION["idOrganisation"] ?? false;

// var_dump($rights);

if($rights === "admin")
{
    $action = GETPOST('action');
    $idUser = GETPOST('idUser');
    $envoi = GETPOST('envoi');
    $firstname = GETPOST('firstname');
    $lastname = GETPOST('lastname');
    $email = GETPOST('email');
    $idPoste = GETPOST('idPoste');
    $idEquipe = GETPOST('idEquipe');
    $birth = GETPOST('birth');
    $oldmdp = GETPOST('oldmdp');
    $newmdp = GETPOST('newmdp');
    $newmdp2 = GETPOST('newmdp2');

    $User = new User($idUser);
    $Poste = new Poste();
    $Equipe = new Equipe();

    $postes = $Poste->fetchAll($idOrganisation);
    $equipes = $Equipe->fetchAll($idOrganisation);

    $erreurs = array();
    $success = false;

    $data = new stdClass;

    $tpl = "inscriptionUtilisateur.php";

    
    // exit;
    
    if($action == "signup")
    {
        if($envoi)
        {
            if($email && $firstname && $lastname && $birth && $idPoste && $idEquipe)
            {
                if(filter_var($email, FILTER_VALIDATE_EMAIL))
                {
                    $idPoste = intval($idPoste);
                    if(is_int($idPoste))
                    {
                        $idEquipe = intval($idEquipe); 
                        if(is_int($idEquipe))
                        {
                            $speciaux = "/[.!@#$%^&*()_+=]/";
                            $nombres = "/[0-9]/";
                            if(!preg_match($nombres, $firstname) && !preg_match($speciaux, $firstname))
                            {
                                if(!preg_match($nombres, $lastname) && !preg_match($speciaux, $lastname))
                                {
                                    if(!$User->verifEmail($email))
                                    {
                                        $temporaryPassword = generateRandomString(6);
                                        $mdp = password_hash($temporaryPassword, PASSWORD_BCRYPT);
                                       

                                        if($User->create($firstname, $lastname, $birth, $idPoste, $idEquipe, $email, $idOrganisation, $mdp))
                                        {
                                            $Organisation = new Organisation($idOrganisation);
                                            $nomOrganisation = $Organisation->getNom();

                                            $subject = "New registration !";

                                            $mailText = "You have been registered by $nomOrganisation on StoriesHelper <br>";
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
                                                $erreurs[] = 'Message could not be sent. Mailer Error: '.$mail->ErrorInfo; 
                                            } else { 
                                                // $success = "Le collaborateur a bien été inscrit."; 
                                                // $success .= " Un email contenant son mot de passe temporaire lui a été envoyé.";    
                                            } 

                                            // mail($email, $subject ,$mailText);

                                            $success = "Le collaborateur a bien été inscrit."; 
                                            $success .= " Un email contenant son mot de passe temporaire lui a été envoyé.";

                                        }
                                        else 
                                        {
                                            $erreurs[] = "Erreur : L'inscription n'a pas pu aboutir.";
                                        }
                                    } 
                                    else 
                                    {
                                        $erreurs[] = "Erreur : L'adresse email est déjà prise.";
                                    }
                                } 
                                else 
                                {
                                    $erreurs[] = "Erreurs : Le nom n'est pas correct.";
                                }
                            } 
                            else 
                            {
                                $erreurs[] = "Erreur : Le prénom n'est pas correct.";
                            }
                        } 
                        else 
                        {
                            $erreurs[] = "L'équipe n'est pas correct.";
                        }
                    } 
                    else 
                    {
                        $erreurs[] = "Le poste n'est pas correct.";
                    }
                } 
                else 
                {
                    $erreurs[] = "Le format de l'adresse email n'est pas correct.";
                }
            } 
            else 
            {
                $erreurs[] = "Un champs n'est pas rempli.";
            }
        } 
    }


    $data = array(
        "success" => $success,
        "erreurs" => $erreurs,
        "postes" => $postes,
        "equipes" => $equipes
    );
    
    $data = json_encode($data);
    
    header("location:".VIEWS_URL."admin/".$tpl."?data=$data");
}
else
{
    header("location:".ROOT_URL."index.php");
}


?>