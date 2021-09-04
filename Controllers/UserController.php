<?php
$action = $_GET["action"] ?? false;
$idUser = $_GET["idUser"] ?? false;

$envoi = $_POST["envoi"] ?? false;

$firstname = $_POST["prenom"] ?? false;
$lastname = $_POST["nom"] ?? false;
$email = $_POST["email"] ?? false;
$idPoste = $_POST["idPoste"] ?? false;
$idEquipe = $_POST["idEquipe"] ?? false;
$birth = $_POST["birth"] ?? false;

$oldmdp = $_POST["oldmdp"] ?? false;
$newmdp = $_POST["newmdp"] ?? false;
$newmdp2 = $_POST["newmdp2"] ?? false;

$rights = $_SESSION["habilitation"] ?? false;
$idOrganisation = $_SESSION["idOrganisation"] ?? false;

$User = new User($idUser);
$Poste = new Poste();
$Equipe = new Equipe();

$page = $_SERVER["REQUEST_URI"];

if($page == 'listeMembres.php')
{
    $membres = $User->fetchAll($idOrganisation);
    $postes = $Poste->fetchAll($idOrganisation);
    $equipes = $Equipe->fetchAll($idOrganisation);
}


if($action == "updateFirstname")
{
    if($idUser && $firstname && $rights == "admin")
    {
        if($envoi)
        {
            $userFirstname = $User->getFirstname();
        
            if($firstname != $userFirstname)
            {
                try
                {
                    $User->updateFirstname($firstname);
                } 
                catch (exception $e)
                {
                    $erreur = "Le prénom n'a pas pu être modifié.";
                }
                $success = "Le prénom a bien été modifié.";
            } 
            else 
            {
                $erreur = "Le nom est le même qu'avant.";
            }
        } 
        else 
        {
            header("location:".ROOT_PATH."/index.php");
        }
    } 
    else 
    {
        header("location:".ROOT_PATH."/index.php");
    }
}


if($action == "updateLastname")
{
    if($idUser && $lastname && $rights == "admin")
    {
        if($envoi)
        {
            $userLastname = $User->getLastname();
        
            if($lastname != $userLastname)
            {
                try
                {
                    $User->updateLastname($lastname);
                } 
                catch (exception $e)
                {
                    $erreur = "La modification de nom n'a pas pu aboutir.";
                }
                $success = "Le nom a bien été modifié.";
            } 
            else 
            {
                $erreur = "Le nom n'a pas été changé.";
            }
        } 
        else
        {
            header("location:".ROOT_PATH."index.php");
        }
    }
    else
    {
        header("location:".ROOT_PATH."index.php");
    }
}


if($action == "updatePoste")
{
    if($idUser && $idPoste && $rights == "admin")
    {
        try 
        {
            $User->updatePoste($idPoste);
        }
        catch (exception $e)
        {
            $erreur = "La modification de poste n'a pas pu aboutir.";
        }
        $success = "La modification de poste a bien été prise en compte.";
    } 
    else
    {
        header("location:".ROOT_PATH."index.php");
    }
}


if($action == "updateEquipe")
{
    if($idUser && $idEquipe && $rights == "admin")
    {
        try
        {
            $User->updateEquipe($idEquipe);
        } 
        catch (exception $e)
        {
            $erreur = "La modification d'équipe n'a pas pu aboutir.";
        }
        $success = "Le modification d'équipe a bien été prise en compte.";
    } 
    else
    {
        header("location:".ROOT_PATH."index.php");
    }
}


if($action == "updatePassword")
{
    if($envoi)
    {
        if(!empty($oldmdp) && !empty($newmdp) && !empty($newmdp2))
        {
            if($newmdp === $newmdp2)
            {
                if (strlen($newmdp) < 8 || strlen($newmdp) > 100)
                {
                    $erreur = "Erreur : Le mot de passe doit contenir entre 8 et 100 caractères, au moins un caractère spécial, une minuscule, une majuscule, un chiffre et ne doit pas contenir d'espace.";
                } 
                else
                {
                    $oldmdp = $User->getPassword();
                    if(!password_verify($oldmdp, hash($newmdp, PASSWORD_BCRYPT)))
                    {
                        $erreur = "L'ancien mot de passe est incorrect.";
                    } 
                    else 
                    {
                        if($oldmdp == hash($newmdp, PASSWORD_BCRYPT))
                        {
                            $erreur = "Erreur : Le mot de passe ne peut pas être le même qu'avant.";
                        } 
                        else 
                        {
                            try
                            {
                                $User->updatePassword(hash($newmdp, PASSWORD_BCRYPT));
                            } 
                            catch (Exception $e) 
                            {
                                $erreur = "Erreur SQL : Le mot de passe n'a pas pu être changé.";
                            }
                            $success = true;
                        }
                    }
                }  
            } 
            else 
            {
                $erreur = "Erreur : Les deux nouveaux mots de passes ne sont pas identiques.";
            }
        } 
        else
        {
            $erreur = "Erreur : Un champs n'est pas rempli.";
        }

    } 
    else 
    {
        header("location:".ROOT_PATH."index.php");
    }
}


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
                                if($User->verifEmail($email))
                                {
                                    if($User->create($prenom, $lastname, $birth, $idPoste, $idEquipe, $email, $idOrganisation))
                                    {
                                        $success = true;
                                    } 
                                    else 
                                    {
                                        $erreur = "Erreur : L'inscription n'a pas pu aboutir.";
                                    }
                                } 
                                else 
                                {
                                    $erreur = "Erreur : L'adresse email est déjà prise.";
                                }
                            } 
                            else 
                            {
                                $erreur = "Erreurs : Le nom n'est pas correct.";
                            }
                        } 
                        else 
                        {
                            $erreur = "Erreur : Le prénom n'est pas correct.";
                        }
                    } 
                    else 
                    {
                        $erreur = "L'équipe n'est pas correct.";
                    }
                } 
                else 
                {
                    $erreur = "Le poste n'est pas correct.";
                }
            } 
            else 
            {
                $erreur = "Le format de l'adresse email n'est pas correct.";
            }
        } 
        else 
        {
            $erreur = "Un champs n'est pas rempli.";
        }
    } 
    else 
    {
        header("location:".VIEWS_PATH."admin/inscriptionUtilisateur.php");
    }
}
?>