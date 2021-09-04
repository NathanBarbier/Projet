<?php
//import all models
require_once "../traitements/header.php";

$idOrganisation = $_SESSION["idOrganisation"] ?? false;

$action = $_GET["action"] ?? $_POST["action"] ?? false;
$envoi = $_GET["envoi"] ?? $_POST["envoi"] ?? false;

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

$erreurs = array();
$success = false;

$data = array();

if($action == "inscriptionUser")
{
    $tpl = "inscriptionUtilisateur.php";

}

// var_dump($_POST);
// exit;

if($action == "inscriptionOrg")
{
    $tpl = "general/inscriptionOrganisation.php";
    
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
                                $success = $Inscription->inscriptionOrg($email, $mdp, $nom);
                            } 
                            catch (exception $e) 
                            {
                                $erreurs[] = "Erreur : l'inscription n'a pas pu aboutir.";
                            }
                        } 
                        else 
                        {
                            $erreurs[] = "Erreur : Les mots de passe ne sont pas identiques.";
                        }
                    }
                    else 
                    {
                        $erreurs[] = "Erreur : L'Email est indisponible.";
                    }
                } 
                else 
                {
                    $erreurs[] = "Erreur : L'Email n'est pas correct.";
                }
            } 
            else 
            {
                $erreurs[] = "Erreur : Le nom est indisponible.";
            }
        } 
        else 
        {
            $erreurs[] = "Erreur : Tous les champs doivent être remplis.";
        }
    } 
    else 
    {        
        header("location:".ROOT_URL."index.php");
    }
}

$data = array(
    "success" => $success,
    "erreurs" => $erreurs,
    "postes" => $postes,
    "equipes" => $equipes
);

$data = json_encode($data);

header("location:".VIEWS_URL.$tpl."?data=$data");

?>