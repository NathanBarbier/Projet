<?php
//import all models
require_once "../../traitements/header.php";

$idOrganisation = $_SESSION["idOrganisation"] ?? false;

$action = GETPOST('action');
$idProjet = GETPOST('idProjet');

$titre = GETPOST('titre');
$type = GETPOST('type');
$deadline = GETPOST('deadline');
$idClient = GETPOST('idClient');
$chefProjet = GETPOST('chefProjet');
$description = GETPOST('description');
$envoi = GETPOST('envoi');
$clientName = GETPOST('clientName');

$rights = $_SESSION["habilitation"] ?? false;

if($rights == 'admin')
{

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

    $erreurs = array();
    $success = false;

    $data = new stdClass;

    $tpl = "creationProjets.php";

    if($action == "addProjet")
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
                    } 
                    catch (exception $e) 
                    {
                        $erreurs[] = "Une erreur est survenue.";
                    }

                    if(empty($erreurs))
                    {
                        $success = true;
                    }
                } 
                else 
                {
                    // le client n'existe pas dans la bdd
                    try 
                    {
                        $Client->create($clientName);
                        $Projet->create($titre, $type, $deadline, $idClient, $idChefProjet, $description);

                        for($i = 0; $i < strlen($equipesProjet); $i++ )
                        {
                            $WorkTo->create($idProjet, $equipesAjoutees[$i]);
                        }
                    } 
                    catch (exception $e) 
                    {
                        $erreurs[] = "Une erreur est survenue.";
                    }

                    if(empty($erreurs))
                    {
                        $success = true;
                    }
                }
            } 
            else 
            {
                $erreurs[] = "Tous les champs ne sont pas remplis.";
            }
        } 
        else 
        {
            header('location:'.ROOT_PATH.'index.php');
        }
    }

    $idProjet = $_GET["idProjet"] ?? false;

    $data = array(
        "success" => $success,
        "erreurs" => $erreurs,
        "equipes" => $equipes,
        "clients" => $clients,
        "chefsEquipes" => $chefsEquipes,
        "maxIdProjet" => $maxIdProjet,
        "idProjet" => $idProjet,
        "type" => $type,
        "deadline" => $deadline,
        "idClient" => $idClient,
        "chefProjet" => $chefProjet,
        "description" => $description,
        "clientName" => $clientName
    );

    $data = json_encode($data);

    header("location:".VIEWS_URL."admin/".$tpl."?data=$data");

} 
else 
{
    header('location:'.ROOT_PATH.'index.php');
}

?>