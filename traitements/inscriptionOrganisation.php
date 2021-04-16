<?php
require_once "header.php";
if(isset($_POST["envoi"]) && !empty($_POST["envoi"])) {
    extract($_POST);
    if(!empty($organisation) && !empty($mail) && !empty($mdp) && !empty($mdp2))
    {
        if(verifNomOrg($organisation) == false )
        {
            if(filter_var($mail, FILTER_VALIDATE_EMAIL ))
            {
                if(verifEmailOrg($mail) == false )
                {
                    if($mdp == $mdp2)
                    {
                        try {
                            $mdp = password_hash($mdp, PASSWORD_BCRYPT);
                            inscriptionOrg($mail, $mdp, $organisation);
                            header("location:../pages/inscriptionOrganisation.php?success=1");
                        } catch (exception $e) {
                            header("location:../pages/inscriptionOrganisation.php?error=fatalerror");
                        }
                    } else {
                        header("location:../pages/inscriptionOrganisation.php?error=nonidentique");
                    }

                } else {
                    header("location:../pages/inscriptionOrganisation.php?error=emailindisponible");
                }

            } else {
                header("location:../pages/inscriptionOrganisation.php?error=emailincorrect");
            }

        } else {
            header("location:../pages/inscriptionOrganisation.php?error=nomindisponible");
        }

    } else {
        header("location:../pages/inscriptionOrganisation.php?error=champsvide");
    }

} else {
    header("location:../index.php");
}