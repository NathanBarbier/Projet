<?php
//import all models
require_once "../../services/header.php";

$rights = $_SESSION["rights"] ?? false;
$idUser = $_SESSION["idUser"] ?? false;

$action = GETPOST('action');
$envoi = GETPOST('envoi');
$email = GETPOST('email');
$name = GETPOST('name');
$pwd = GETPOST('pwd');
$pwd2 = GETPOST('pwd2');
$consent = GETPOST('consent');
if ($consent == "on")
{
    $consent = true;
}

$Organization = new Organization();
$User = new User();

$errors = array();
$success = false;

$tpl = "inscriptionOrganisation.php";

if($action == "inscriptionOrg")
{
    if($envoi) 
    {
        if($name && $email && $pwd && $pwd2 && $consent)
        {
            if($Organization->checkByName($name) == false)
            {
                if(filter_var($email, FILTER_VALIDATE_EMAIL))
                {
                    if($User->checkByEmail($email) == false)
                    {
                        if($pwd === $pwd2)
                        {
                            // $speciaux = "/[.!@#$%^&*()_+=]/";
                            // $nombres = "/[0-9]/";
                            // if(preg_match($nombres, $pwd) && !preg_match($speciaux, $pwd) && strlen($pwd) >= 8 && strlen($pwd) <= 100)
                            try
                            {
                                $fk_organization = intval($Organization->fetch_last_insert_id()) + 1;
                                $idUser = intval($User->fetch_last_insert_id()) + 1;

                                $User->setEmail($email);
                                $User->setPassword($pwd);
                                $User->setFk_organization($fk_organization);
                                $User->setAdmin(1);
                                $User->setConsent(1);
                                $User->create();
                                LogHistory::create($idUser, 'signup', 'user', $User->getEmail());

                                $Organization->setName($name);
                                $Organization->create();
                                LogHistory::create($idUser, 'create', 'Organization', $Organization->getName());
                            } 
                            catch (exception $e) 
                            {
                                $errors[] = "Erreur : l'inscription n'a pas pu aboutir.";
                            }

                            if(!in_array(false, $status))
                            {
                                $message = "L'inscription a bien été prise en compte";
                                header("location:".CONTROLLERS_URL.'visiteur/connexion.php?message='.$message);
                                exit;
                            }
                            else
                            {
                                $errors[] = "Une erreur inconnue est survenue.";
                            }
                        } 
                        else 
                        {
                            $errors[] = "Erreur : Les mots de passe ne sont pas identiques.";
                        }
                    }
                    else 
                    {
                        $errors[] = "Erreur : L'Email est indisponible.";
                    }
                } 
                else 
                {
                    $errors[] = "Erreur : L'Email n'est pas correct.";
                }
            } 
            else 
            {
                $errors[] = "Erreur : Le nom est indisponible.";
            }
        } 
        else 
        {
            $errors[] = "Erreur : Tous les champs doivent être remplis.";
        }
    } 
    else 
    {        
        header("location:".ROOT_URL."index.php");
    }
}


require_once VIEWS_PATH."visiteur/".$tpl;

?>