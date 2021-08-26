<?php

$idOrganisation = $_SESSION["idOrganisation"] ?? false;

$action = $_GET["action"] ?? false;
$idProjet = $_GET["idProjet"] ?? false;

$titre = $_POST["titre"] ?? false;
$type = $_POST["type"] ?? false;
$deadline = $_POST["deadline"] ?? false;
$idClient = $_POST["idClient"] ?? false;
$chefProjet = $_POST["chefProjet"] ?? false;
$description = $_POST["description"] ?? false;
$envoi = $_POST["envoi"] ?? false;
$idProjet = $_POST["idProjet"] ?? false;
$clientName = $_POST["clientName"] ?? false;


$Equipe = new Equipe();
$Client = new Client();
$Projet = new Projet();
$WorkTo = new WorkTo();

$equipes = $Equipe->fetchAll($idOrganisation);
$clients = $Client->fetchAll($idOrganisation);

foreach($equipes as $key => $equipe)
{
    $chefsEquipes[$key][] = $Equipe->fetchChef($equipe["idEquipe"]);
}

$maxIdProjet = $Projet->fetchMaxId()["maxId"];


//! WIP
if($action == "addProjet")
{
    if($rights == 'admin')
    {
        if($envoi || $idProjet)
        {
            if($titre && $type && $description && $clientName && $chefProjet && $equipesAjoutees)
            {
                $nomPrenomChefProjet = explode(" ", $chefProjet);
                $prenomChef = $nomPrenomChefProjet[0];
                $nomChef = $nomPrenomChefProjet[1];

                $idChefProjet = $User->fetchByLastnameAndFirstname($nomChef, $prenomChef, $idOrganisation)["idUtilisateur"];
                $idClient = $Client->fetchId($clientName)["idClient"];

                if($Client->checkByName($clientName))
                {
                    // le client existe dans la bdd
                    try 
                    {
                        $Projet->create($titre, $type, $deadline, $idClient, $chefProjet, $description);


                        for($i = 0; $i < strlen($equipesProjet); $i++ )
                        {
                            $WorkTo->create($idProjet, $equipesAjoutees[$i]);
                        }
                        header("location:".VIEWS_PATH."admin/creationProjets.php?success=1");
                    } 
                    catch (exception $e) 
                    {
                        header("location:".VIEWS_PATH."/admin/creationProjets.php?error=fatalError&idProjet=$idProjet");
                    }
                } 
                else 
                {
                    // le client n'existe pas dans la bdd
                    try 
                    {
                        $Client->create($clientName);
                        $Projet->create($titre, $type, $deadline, $idClient, $idChefProjet, $description);
                        // print_r($equipesAjoutees);
                        // exit;
                        for($i = 0; $i < strlen($equipesProjet); $i++ )
                        {
                            $WorkTo->create($idProjet, $equipesAjoutees[$i]);
                        }
                        header("location:".VIEWS_PATH."admin/creationProjets.php?success=1");
                    } 
                    catch (exception $e) 
                    {
                        echo '<div class="alert alert-danger">';
                        echo $e->getMessage();
                        echo "</div>";
                        exit;
                        header("location:".VIEWS_PATH."admin/creationProjets.php?error=fatalError&idProjet=$idProjet");
                    }
                }
            } 
            else 
            {
                header("location:".VIEWS_PATH."admin/creationProjets.php?error=champsVide&idProjet=$idProjet");
            }
        } 
        else 
        {
            header('location:'.ROOT_PATH.'index.php');
        }
    } 
    else 
    {
        header('location:'.ROOT_PATH.'index.php');
    }
}


?>