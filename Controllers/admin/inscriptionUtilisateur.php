<?php
//import all models
require_once "../../traitements/header.php";

$action = $_GET["action"] ?? false;
$idUser = $_GET["idUser"] ?? false;

$envoi = $_POST["envoi"] ?? false;

$firstname = $_POST["prenom"] ?? false;
$lastname = $_POST["nom"] ?? false;
$email = $_POST["email"] ?? false;
$idPoste = $_POST["idPoste"] ?? false;
$idEquipe = $_POST["idEquipe"] ?? false;
$birth = $_POST["birth"] ?? false;

$oldmdp = $_POST["oldmdp"] ?? false;
$newmdp = $_POST["newmdp"] ?? false;
$newmdp2 = $_POST["newmdp2"] ?? false;

$rights = $_SESSION["habilitation"] ?? false;
$idOrganisation = $_SESSION["idOrganisation"] ?? false;

$User = new User($idUser);

$Poste = new Poste();
$postes = $Poste->fetchAll($idOrganisation);

$Equipe = new Equipe();
$equipes = $Equipe->fetchAll($idOrganisation);

$erreurs = array();
$success = false;

$data = array();

$tpl = "inscriptionUtilisateur.php";

if($right === "admin")
{   
    if($action == "signup")
    {
        if($envoi)
        {
            if($email && $firstname && $lastname && $birth && $idPoste && $idEquipe)
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
                            if(!preg_match($nombres, $firstname) && !preg_match($speciaux, $firstname))
                            {
                                if(!preg_match($nombres, $lastname) && !preg_match($speciaux, $lastname))
                                {
                                    if($User->verifEmail($email))
                                    {
                                        if($User->create($prenom, $lastname, $birth, $idPoste, $idEquipe, $email, $idOrganisation))
                                        {
                                            $success = true;
                                        } 
                                        else 
                                        {
                                            $erreurs[] = "Erreur : L'inscription n'a pas pu aboutir.";
                                        }
                                    } 
                                    else 
                                    {
                                        $erreurs[] = "Erreur : L'adresse email est déjà prise.";
                                    }
                                } 
                                else 
                                {
                                    $erreurs[] = "Erreurs : Le nom n'est pas correct.";
                                }
                            } 
                            else 
                            {
                                $erreurs[] = "Erreur : Le prénom n'est pas correct.";
                            }
                        } 
                        else 
                        {
                            $erreurs[] = "L'équipe n'est pas correct.";
                        }
                    } 
                    else 
                    {
                        $erreurs[] = "Le poste n'est pas correct.";
                    }
                } 
                else 
                {
                    $erreurs[] = "Le format de l'adresse email n'est pas correct.";
                }
            } 
            else 
            {
                $erreurs[] = "Un champs n'est pas rempli.";
            }
        } 
    }
}

$data = array(
    "success" => $success,
    "erreurs" => $erreurs,
    "postes" => $postes,
    "equipes" => $equipes
);

$data = json_encode($data);

header("location:".VIEWS_URL."admin/".$tpl."?data=$data");

?>