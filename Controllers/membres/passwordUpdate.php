<?php
//import all models
require_once "../../traitements/header.php";

$idUser = $_SESSION["idUtilisateur"] ?? null;
$rights = $_SESSION["habilitation"] ?? false;
$idOrganisation = $_SESSION["idOrganisation"] ?? false;

if($rights === 'user')
{
    $action = GETPOST('action');
    $envoi = GETPOST('envoi');
    
    $firstname = GETPOST('prenom');
    $lastname = GETPOST('nom');
    $email = GETPOST('email');
    $idPoste = GETPOST('idPoste');
    $idEquipe = GETPOST('idEquipe');
    $birth = GETPOST('birth');
    
    $oldmdp = GETPOST('oldmdp');
    $newmdp = GETPOST('newmdp');
    $newmdp2 = GETPOST('newmdp');
    
    
    $User = new User($idUser);
    $Poste = new Poste();
    $Equipe = new Equipe();
    
    $tpl = "passwordUpdate.php";
    
    $erreurs = array();
    $success = false;
    
    $data = new stdClass;
    
    if($action == "passwordUpdate")
    {
        if($envoi)
        {
    
            // var_dump($oldmdp, $newmdp, $newmdp2);
            // exit;
    
            if($oldmdp && $newmdp && $newmdp2)
            {
                if($newmdp === $newmdp2)
                {
                    if (strlen($newmdp) < 8 || strlen($newmdp) > 100)
                    {
                        $erreurs[] = "Erreur : Le mot de passe doit contenir entre 8 et 100 caractères, au moins un caractère spécial, une minuscule, une majuscule, un chiffre et ne doit pas contenir d'espace.";
                    } 
                    else
                    {
                        $newmdp = password_hash($newmdp, PASSWORD_BCRYPT);
    
                        $oldmdpbdd = $User->getPassword();
    
                        // var_dump($User);
                        // var_dump($oldmdp, $oldmdpbdd);
                        // exit;
    
                        if(!password_verify($oldmdp, $oldmdpbdd))
                        {
                            $erreurs[] = "L'ancien mot de passe est incorrect.";
                        } 
                        else 
                        {
                            if($oldmdp == $newmdp)
                            {
                                $erreurs[] = "Erreur : Le mot de passe ne peut pas être le même qu'avant.";
                            } 
                            else 
                            {
                                $status = $User->updatePassword($newmdp);
    
                                if($status)
                                {
                                    $success = true;
                                }
                                else
                                {
                                    $erreurs[] = "Erreur SQL : Le mot de passe n'a pas pu être changé.";
                                }
    
                            }
                        }
                    }  
                } 
                else 
                {
                    $erreurs[] = "Erreur : Les deux nouveaux mots de passes ne sont pas identiques.";
                }
            } 
            else
            {
                $erreurs[] = "Erreur : Un champs n'est pas rempli.";
            }
    
        } 
        else 
        {
            header("location:".ROOT_PATH."index.php");
        }
    }
    
    
    $data = array(
        'erreurs' => $erreurs,
        'success' => $success,
    );
    
    $data = json_encode($data);
    
    header("location:".VIEWS_URL."membres/".$tpl."?data=$data");
}
else
{
    header("location:".ROOT_URL."index.php");
}





?>