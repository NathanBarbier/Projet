<?php
//import all models
require_once "../../services/header.php";

$idUser = $_SESSION["idUser"] ?? null;
$rights = $_SESSION["rights"] ?? false;
$idOrganization = $_SESSION["idOrganization"] ?? false;

if($rights === 'user')
{
    $action = GETPOST('action');
    $envoi = GETPOST('envoi');
    
    $firstname = GETPOST('prenom');
    $lastname = GETPOST('nom');
    $email = GETPOST('email');
    $idTeam = GETPOST('idTeam');
    $birth = GETPOST('birth');
    
    $oldmdp = GETPOST('oldmdp');
    $newmdp = GETPOST('newmdp');
    $newmdp2 = GETPOST('newmdp2');
     
    $User = new User($idUser);
    
    $tpl = "passwordUpdate.php";
    
    $errors = array();
    $success = false;
    
    if($action == "passwordUpdate")
    {
        if($envoi)
        {    
            if($oldmdp && $newmdp && $newmdp2)
            {
                if($newmdp === $newmdp2)
                {
                    if (strlen($newmdp) < 8 || strlen($newmdp) > 100)
                    {
                        $errors[] = "Le mot de passe doit contenir entre 8 et 100 caractères, au moins un caractère spécial, une minuscule, une majuscule, un chiffre et ne doit pas contenir d'espace.";
                    } 
                    else
                    {
                        if(password_verify($oldmdp, $User->getPassword()))
                        {
                            if($oldmdp != $newmdp)
                            {
                                try {
                                    $User->setPassword($newmdp);
                                    $User->update();
                                    LogHistory::create($idUser, 'update password', 'user', $User->getLastname().' '.$User->getFirstname());
                                    $success = "Le mot de passe a bien été modifié.";
                                    header("location:".CONTROLLERS_URL."membre/tableauDeBord.php?success=".$success);
                                    exit;
                                } catch (\Throwable $th) {
                                    //throw $th;
                                    $errors[] = "Une erreur innatendue est survenue.";
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

    require_once VIEWS_PATH."membre/".$tpl;
}
else
{
    header("location:".ROOT_URL."index.php");
}





?>