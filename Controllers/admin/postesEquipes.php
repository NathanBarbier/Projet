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
    $chefEquipes = $Organisation->getInfosChefsEquipes($idOrganisation);
    
    //TODO OPTI ???
    $roles = $Role->fetchAll();
    $equipes = $Equipe->fetchAll($idOrganisation);
    $postes = $Poste->fetchAll($idOrganisation);

    // var_dump($postes);
    // exit;
    
    
    foreach($equipes as $key => $equipe)
    {
        $membresEquipes[$key][] = $User->fetchByEquipe($equipe["idEquipe"]);
        $projetsEquipes[$key][] = $Projet->fetchByEquipe($equipe["idEquipe"]);
    }
    
    foreach($membresEquipes as $equipekey => $equipe)
    {
        foreach($equipe as $membrekey => $membre)
        {
            $membresEquipes[$equipekey][$membrekey]["poste"] = $Poste->fetch($membre["idPoste"]);
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
            $Poste->delete($idPoste);
        }
        catch(Exception $e)
        {
            $erreurs[] = "Une erreur inconnue est survenue.";
        }

        if(empty($erreurs))
        {
            $success = "Le poste a bien été supprimmé.";
        }
    }
    
    if($action == "updatePoste")
    {
        try
        {
            $Poste->updateName($nomPoste);
        }
        catch(Exception $e)
        {
            $erreurs[] = "Une erreur inconnue est survenue.";
        }

        if(empty($erreurs))
        {
            $success = "Le poste a bien été modifié.";
        }
    }
    
    if($action == "addPoste")
    {
        try
        {
            $Poste->create($nomPoste, $idOrganisation, $idRole);
        }
        catch(Exception $e)
        {
            $erreurs[] = "Une erreur inconnue est survenue.";
        }

        if(empty($erreurs))
        {
            $success = "Le poste a bien été ajouté.";
        }
    }
    
    if($action == "addEquipe")
    {
        try
        {
            $Equipe->create($nomEquipe, $idOrganisation);
        }
        catch(Exception $e)
        {
            $erreurs[] = "Une erreur inconnue est survenue.";
        }

        if(empty($erreurs))
        {
            $success = "L'équipe a bien été ajoutée.";
        }
    }
    
    $data = array(
        'nbMembresEquipes' => $nbMembresEquipes,
        'nbMembresPostes' => $nbMembresPostes,
        'equipeMinMax' => $equipeMinMax,
        'chefEquipes' => $chefEquipes,
        'roles' => $roles,
        'equipes' => $equipes,
        'postes' => $postes,
        'membresEquipes' => $membresEquipes, 
        'projetsEquipes' => $projetsEquipes,
        'fetchPoste' => $fetchPoste,
        'erreurs' => $erreurs,
        'success' => $success,
        'deletePoste' => $deletePoste,
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