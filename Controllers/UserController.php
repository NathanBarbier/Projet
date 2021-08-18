<?php require_once "header.php";

$action = $_GET["action"] ? $_GET["action"] : false;
$idUser = $_GET["idUser"] ? $_GET["idUser"] : false;

$envoi = $_POST["envoi"] ? $_POST["envoi"] : false;

$firstname = $_POST["prenom"] ? $_POST["prenom"] : false;
$lastname = $_POST["nom"] ? $_POST["nom"] : false;
$email = $_POST["email"] ? $_POST["email"] : false;
$idPoste = $_POST["idPoste"] ? $_POST["idPoste"] : false;
$idEquipe = $_POST["idEquipe"] ? $_POST["idEquipe"] : false;
$birth = $_POST["dateNaiss"] ? $_POST["dateNaiss"] : false;

$oldmdp = $_POST["oldmdp"] ? $_POST["oldmdp"] : false;
$newmdp = $_POST["newmdp"] ? $_POST["newmdp"] : false;
$newmdp2 = $_POST["newmdp2"] ? $_POST["newmdp2"] : false;

$rights = $_SESSION["habilitation"] ? $_SESSION["habilitation"] : false;

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
                    header('location:../admin/listeMembres.php?error=modifPrenomFatalError');
                }
                header("location:../admin/listeMembres.php?success=modifierPrenom");
            } 
            else 
            {
                header("location:../admin/listeMembres.php?error=surnameNoChange");
            }
        } 
        else 
        {
            header("location:../index.php");
        }
    } 
    else 
    {
        header("location:../index.php");
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
                    header('location:../admin/listeMembres.php?error=modifNomFatalError');
                }
                header("location:../admin/listeMembres.php?success=modifierNom");
            } 
            else 
            {
                header("location:../admin/listeMembres.php?error=nameNoChange");
            }
        } 
        else
        {
            header("location:../index.php");
        }
    }
    else
    {
        header("location:../index.php");
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
            header('location:../admin/listeMembres.php?error=ModifPosteFatalError');
        }
        header("../admin/listeMembres.php?success=modifierPoste");
    } 
    else
    {
        header("location:../index.php");
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
            header('location:../admin/listeMembres.php?error=ModifEquipeFatalError');
        }
        header("../admin/listeMembres.php?success=modifierEquipe");
    } 
    else
    {
        header("location:../index.php");
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
                    header('location:../membres/modificationMdpUser.php?error=mdpRules');
                } 
                else
                {
                    $oldmdp = $User->getPassword();
                    if(!password_verify($oldmdp, hash($newmdp, PASSWORD_BCRYPT)))
                    {
                        header('location:../membres/modificationMdpUser.php?error=incorrectMdp');
                    } 
                    else 
                    {
                        if($oldmdp == hash($newmdp, PASSWORD_BCRYPT))
                        {
                            header('location:../membres/modificationMdpUser.php?error=noChange');
                        } 
                        else 
                        {
                            try
                            {
                                $User->updatePassword(hash($newmdp, PASSWORD_BCRYPT));
                            } 
                            catch (Exception $e) 
                            {
                                ?>
                                <div class="alert alert-danger">
                                    Erreur SQL : Le mot de passe n'a pas pu être changé.
                                </div>
                                <?php
                            }
                            header("location;../membres/modificationMdpUser.php?success");
                        }
                    }
                }  
            } 
            else 
            {
                header('location:../membres/modificationMdpUser.php?error=nonIdentiques');
            }
        } 
        else
        {
            header('location:../membres/modificationMdpUser.php?error=missingInput');
        }
        if(count($erreurs) != 0)
        {
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
    } else {
        header("location:../index.php");
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
                                if($User->verifEmail($email) == true)
                                {
                                    if($User->create($prenom, $lastname, $birth, $idPoste, $idEquipe, $email, $idOrganisation) != false)
                                    {
                                        header("location:../admin/inscriptionUtilisateur.php?success=1");
                                    } else {
                                        header("location:../admin/inscriptionUtilisateur.php?error=inscriptionfailed");
                                    }
                                    
                                } else {
                                    header("location:../admin/inscriptionUtilisateur.php?error=emailindisponible");
                                }

                            } else {
                                header("location:../admin/inscriptionUtilisateur.php?error=nommatch");
                            }
                        
                        } else {
                            header("location:../admin/inscriptionUtilisateur.php?error=prenommatch");
                        }

                    } else {
                        header("location:../admin/inscriptionUtilisateur.php?error=idequipeint");
                    }

                } else {
                    header("location:../admin/inscriptionUtilisateur.php?error=idposteint");
                }

            } else {
                header("location:../admin/inscriptionUtilisateur.php?error=emailvalidate");
            }

        } else {
            header("location:../admin/inscriptionUtilisateur.php?error=champsvide");
        }
    } else {
        header("location:../admin/inscriptionUtilisateur.php");
    }
}
?>