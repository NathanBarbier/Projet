<?php 
if(isset($_POST["envoi"]) && !empty($_POST["envoi"])) {
    extract($_POST);
    if(!empty($organisation) && !empty($mail) && !empty($mdp) && !empty($mdp2))
    {
        if(recupNomOrganisation($organisation)->rowcount() == 0 )
        {
            if(filter_var($mail, FILTER_VALIDATE_EMAIL )
            {
                if(recupEmailOrganisation($mail)->rowcount() == 0 )
                {
                    if($mdp == $mdp2)
                    {
                        try {
                            $mdp = password_hash($mdp, PASSWORD_BCRYPT);
                            creerOrganisation($mail, $mdp, $organisation);
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