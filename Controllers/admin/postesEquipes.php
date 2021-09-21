<?php
//import all models
require_once "../../traitements/header.php";

$idOrganisation = $_SESSION["idOrganisation"] ?? false;
$rights = $_SESSION["habilitation"] ?? false;

if($rights === 'admin')
{
    $action = GETPOST('action');
    $error = GETPOST('error');
    $success = GETPOST('success');
    $idPoste = GETPOST('idPoste');
    $nomPoste = GETPOST('nomPoste');
    $idRole = GETPOST('idRole');
    $nomEquipe = GETPOST('nomEquipe');
    
    $erreurs = array();
    $success = false;

    $deletePoste = false;
    $updatePoste = false;
    
    $tpl = "postesEquipes.php";
    
    $Organisation = new Organisation($idOrganisation);
    $Role = new Role();
    $Equipe = new Equipe();
    $Projet = new Projet();
    $User = new User();
    $Poste = new Poste();

    $fetchPoste = $idPoste ? $Poste->fetch($idPoste) : false;
    $nbMembresEquipes = $Organisation->CountUsersByEquipes($idOrganisation);
    $nbMembresPostes = $Organisation->CountUsersByPoste($idOrganisation);
    $equipeMinMax = $Organisation->getMinMaxIdEquipe($idOrganisation);
    
    //TODO OPTI ???
    $roles = $Role->fetchAll();
    $equipes = $Equipe->fetchAll($idOrganisation);
    $postes = $Poste->fetchAll($idOrganisation);
    
    foreach($equipes as $key => $equipe)
    {
        $membresEquipes[$key][] = $User->fetchByEquipe($equipe["idEquipe"]);
        $projetsEquipes[$key][] = $Projet->fetchByEquipe($equipe["idEquipe"]);
    }
    
    foreach($membresEquipes as $equipekey => $equipe)
    {
        foreach($equipe as $membrekey => $membre)
        {
            $membresEquipes[$equipekey][$membrekey]["poste"] = $Poste->fetch($membre[$membrekey]["idPoste"]);
        }
    }   
    
    if($action == "deletePoste")
    {
        $deletePoste = true;
    }
    
    if($action == "deletePosteConf")
    {
        try
        {
            $status = $Poste->delete($idPoste, $idOrganisation);
        }
        catch(Exception $e)
        {
            $erreurs[] = "Une erreur inconnue est survenue.";
        }

        if($status)
        {
            $success = "Le poste a bien été supprimmé.";
        }
        else
        {
            $erreurs[] = "Une erreur inconnue est survenue."; 
        }
    }

    if($action == "updatePoste")
    {
        $updatePoste = true;
    }
    
    if($action == "updatePosteConf")
    {
        try
        {
            $status = $Poste->updateName($nomPoste, $idPoste);
        }
        catch(Exception $e)
        {
            $erreurs[] = "Une erreur inconnue est survenue.";
        }

        if($status)
        {
            $success = "Le poste a bien été modifié.";
        }
        else
        {
            $erreurs[] = "Une erreur inconnue est survenue.";
        }
    }
    
    if($action == "addPoste")
    {
        // var_dump($nomPoste, $idOrganisation, $idRole);
        // exit;
        try
        {
            $status = $Poste->create($nomPoste, $idOrganisation, $idRole);
        }
        catch(Exception $e)
        {
            $erreurs[] = "Une erreur inconnue est survenue.";
        }

        if($status)
        {
            $success = "Le poste a bien été ajouté.";
        }
        else
        {
            $erreurs[] = "Une erreur inconnue est survenue.";
        }
    }
    
    if($action == "addEquipe")
    {
        try
        {
            $status = $Equipe->create($nomEquipe, $idOrganisation);
        }
        catch(Exception $e)
        {
            $erreurs[] = "Une erreur inconnue est survenue.";
        }

        if($status)
        {
            $success = "L'équipe a bien été ajoutée.";
        }
        else
        {
            $erreurs[] = "Une erreur inconnue est survenue.";
        }
    }


    if($success)
    {
        $Organisation = new Organisation($idOrganisation);

        $fetchPoste = $idPoste ? $Poste->fetch($idPoste) : false;
        $nbMembresEquipes = $Organisation->CountUsersByEquipes($idOrganisation);
        $nbMembresPostes = $Organisation->CountUsersByPoste($idOrganisation);
        $equipeMinMax = $Organisation->getMinMaxIdEquipe($idOrganisation);
        
        $roles = $Role->fetchAll();
        $equipes = $Equipe->fetchAll($idOrganisation);
        $postes = $Poste->fetchAll($idOrganisation);
    }
    
    $data = array(
        'nbMembresEquipes' => $nbMembresEquipes,
        'nbMembresPostes' => $nbMembresPostes,
        'equipeMinMax' => $equipeMinMax,
        'roles' => $roles,
        'equipes' => $equipes,
        'postes' => $postes,
        'membresEquipes' => $membresEquipes, 
        'projetsEquipes' => $projetsEquipes,
        'fetchPoste' => $fetchPoste,
        'erreurs' => $erreurs,
        'success' => $success,
        'deletePoste' => $deletePoste,
        'updatePoste' => $updatePoste,
        'idPoste' => $idPoste
    );

    $data = json_encode($data);
    
    header("location:".VIEWS_URL."admin/".$tpl."?data=$data");
}
else
{
    header("location:".ROOT_URL."index.php");
}



?>