<?php
require_once 'header.php';
if(!empty($_POST["envoi"]) && $_POST["envoi"] == 1)
{
    extract($_POST);
    if(!empty($email) && !empty($nom) && !empty($prenom) && !empty($dateNaiss) && !empty($idPoste) && !empty($idEquipe))
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
                    if(preg_match($nombres, $prenom) == 0 && preg_match($speciaux, $prenom) == 0)
                    {
                        if(preg_match($nombres, $nom) == 0 && preg_match($speciaux, $nom) == 0)
                        {
                            if(verifEmailUtilisateur($email) == true)
                            {
                                if(creerUtilisateur($nom,$prenom,$dateNaiss,$idPoste,$email,$idEquipe,$_SESSION["idOrganisation"]) != false)
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
