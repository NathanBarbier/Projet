<?php

$idOrganisation = $_SESSION["idOrganisation"] ?? false;

$action = $_GET["action"] ?? false;
$envoi = $_GET["envoi"] ?? false;

$email = $_POST["email"] ?? false;
$nom = $_POST["nom"] ?? false;
$prenom = $_POST["prenom"] ?? false;
$birth = $_POST["birth"] ?? false;
$idPoste = $idPoste["idPoste"] ?? false;
$mdp = $_POST["mdp"] ?? false;
$mdp2 = $_POST["mdp2"] ?? false;

$Organisation = new Organisation();

$Inscription = new Inscription();

$Poste = new Poste();
$postes = $Poste->fetchAll($idOrganisation);

$Equipe = new Equipe();
$equipes = $Equipe->fetchAll($idOrganisation);


if($action == "inscriptionUser")
{

}

if($action == "inscriptionOrg")
{
    if($envoi) 
    {
        if($nom && $email && $mdp && $mdp2)
        {
            if($Organisation->verifNom($nom) == false )
            {
                if(filter_var($email, FILTER_VALIDATE_EMAIL ))
                {
                    if($Organisation->verifEmail($email) == false )
                    {
                        if($mdp == $mdp2)
                        {
                            try
                            {
                                $mdp = password_hash($mdp, PASSWORD_BCRYPT);
                                $Inscription->inscriptionOrg($email, $mdp, $organisation);
                            } 
                            catch (exception $e) 
                            {
                                header("location:".VIEWS_PATH."general/inscriptionOrganisation.php?error=fatalerror");
                            }
                            header("location:".VIEWS_PATH."general/inscriptionOrganisation.php?success=1");
                        } 
                        else 
                        {
                            header("location:".VIEWS_PATH."general/inscriptionOrganisation.php?error=nonidentique");
                        }

                    } 
                    else 
                    {
                        header("location:".VIEWS_PATH."general/inscriptionOrganisation.php?error=emailindisponible");
                    }

                } 
                else 
                {
                    header("location:".VIEWS_PATH."general/inscriptionOrganisation.php?error=emailincorrect");
                }

            } 
            else 
            {
                header("location:".VIEWS_PATH."general/inscriptionOrganisation.php?error=nomindisponible");
            }

        } 
        else 
        {
            header("location:".VIEWS_PATH."general/inscriptionOrganisation.php?error=champsvide");
        }

    } 
    else 
    {
        header("location:".ROOT_PATH."index.php");
    }
}

?>