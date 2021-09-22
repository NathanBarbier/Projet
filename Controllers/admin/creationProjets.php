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

$success = false;
$erreurs = array();

if($rights == 'admin')
{
    $Equipe = new Equipe();
    $Client = new Client();
    $Projet = new Projet();
    $WorkTo = new WorkTo();

    $equipes = $Equipe->fetchAll($idOrganisation);
    $clients = $Client->fetchAll($idOrganisation);

    $maxIdProjet = $Projet->fetchMaxId()["maxId"];

    $erreurs = array();
    $success = false;

    $data = new stdClass;

    $tpl = "creationProjets.php";

    if($action == "addProjet")
    {
        if($envoi || $idProjet)
        {
            if($titre && $type && $description)
            {
                $status = $Projet->create($titre, $type, $deadline, $description, $idOrganisation);

                if($status)
                {
                    $success = "Le projet a été créé avec succès.";
                }
                else
                {
                    $erreurs[] = "Une erreur est survenue.";
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

    $data = array(
        "success" => $success,
        "erreurs" => $erreurs,
        "equipes" => $equipes,
        "maxIdProjet" => $maxIdProjet,
        "idProjet" => $idProjet,
        "titre" => $titre,
        "type" => $type,
        "deadline" => $deadline,
        "description" => $description,
    );

    $data = json_encode($data);

    header("location:".VIEWS_URL."admin/".$tpl."?data=$data");

} 
else 
{
    header('location:'.ROOT_PATH.'index.php');
}

?>