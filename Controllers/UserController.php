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

$User = new User($idUser);

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
                    header('location:'.VIEWS_PATH.'admin/listeMembres.php?error=updatePrenomFatal');
                }
                header("location:".VIEWS_PATH."admin/listeMembres.php?success=prenomUpdate");
            } 
            else 
            {
                header("location:".VIEWS_PATH."admin/listeMembres.php?error=surnameNoChange");
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
                    header('location:'.VIEWS_PATH.'/admin/listeMembres.php?error=lastnameUpdateFatal');
                }
                header("location:".VIEWS_PATH."admin/listeMembres.php?success=lastnameUpdate");
            } 
            else 
            {
                header("location:".VIEWS_PATH."admin/listeMembres.php?error=lastnameNoChange");
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
            header('location:'.VIEWS_PATH.'admin/listeMembres.php?error=posteUpdateFatal');
        }
        header("location:".VIEWS_PATH."admin/listeMembres.php?success=posteUpdate");
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
            header('location:'.VIEWS_PATH.'admin/listeMembres.php?error=equipeUpdateFatal');
        }
        header("location:".VIEWS_PATH."admin/listeMembres.php?success=equipeUpdate");
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
                    header('location:'.VIEWS_PATH.'membres/passwordUpdate.php?error=mdpRules');
                } 
                else
                {
                    $oldmdp = $User->getPassword();
                    if(!password_verify($oldmdp, hash($newmdp, PASSWORD_BCRYPT)))
                    {
                        header('location:'.VIEWS_PATH.'membres/passwordUpdate.php?error=incorrectMdp');
                    } 
                    else 
                    {
                        if($oldmdp == hash($newmdp, PASSWORD_BCRYPT))
                        {
                            header('location:'.VIEWS_PATH.'membres/passwordUpdate.php?error=noChange');
                        } 
                        else 
                        {
                            try
                            {
                                $User->updatePassword(hash($newmdp, PASSWORD_BCRYPT));
                            } 
                            catch (Exception $e) 
                            {
                                /*
                                TODO: ADAPTER ARCHITECTURE CONTROLLER
                                */
                                ?>
                                <div class="alert alert-danger">
                                    Erreur SQL : Le mot de passe n'a pas pu être changé.
                                </div>
                                <?php
                            }
                            header("location:".VIEWS_PATH."membres/passwordUpdate.php?success=1");
                        }
                    }
                }  
            } 
            else 
            {
                header('location:'.VIEWS_PATH.'membres/passwordUpdate.php?error=unmatch');
            }
        } 
        else
        {
            header('location:'.VIEWS_PATH.'membres/passwordUpdate.php?error=missingInput');
        }

        if(count($erreurs) != 0)
        {
        /* 
        TODO : ADAPTER ARCHITECTURE CONTROLLER
        REDIRECTION PAGE UPDATE PASSWORD
        AFFICHER ERREURS
        */
        ?>
            <div class="alert alert-danger">
                <?php
                foreach($erreurs as $erreur)
                {
                    echo $erreur . "<br>";
                }
                ?>
            </div>
        <?php
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
                        if(preg_match($nombres, $firstname) == 0 && preg_match($speciaux, $firstname) == 0)
                        {
                            if(preg_match($nombres, $lastname) == 0 && preg_match($speciaux, $lastname) == 0)
                            {
                                if($User->verifEmail($email))
                                {
                                    if($User->create($prenom, $lastname, $birth, $idPoste, $idEquipe, $email, $idOrganisation))
                                    {
                                        header("location:".VIEWS_PATH."admin/inscriptionUtilisateur.php?success=1");
                                    } 
                                    else 
                                    {
                                        header("location:".VIEWS_PATH."admin/inscriptionUtilisateur.php?error=inscriptionfailed");
                                    }
                                    
                                } 
                                else 
                                {
                                    header("location:".VIEWS_PATH."admin/inscriptionUtilisateur.php?error=emailindisponible");
                                }

                            } 
                            else 
                            {
                                header("location:".VIEWS_PATH."admin/inscriptionUtilisateur.php?error=nommatch");
                            }
                        
                        } 
                        else 
                        {
                            header("location:".VIEWS_PATH."admin/inscriptionUtilisateur.php?error=prenommatch");
                        }

                    } 
                    else 
                    {
                        header("location:".VIEWS_PATH."admin/inscriptionUtilisateur.php?error=idequipeint");
                    }

                } 
                else 
                {
                    header("location:".VIEWS_PATH."admin/inscriptionUtilisateur.php?error=idposteint");
                }

            } 
            else 
            {
                header("location:".VIEWS_PATH."admin/inscriptionUtilisateur.php?error=emailvalidate");
            }

        } 
        else 
        {
            header("location:".VIEWS_PATH."admin/inscriptionUtilisateur.php?error=champsvide");
        }
    } 
    else 
    {
        header("location:".VIEWS_PATH."admin/inscriptionUtilisateur.php");
    }
}
?>