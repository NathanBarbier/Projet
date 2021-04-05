<?php
function recupererHashMdpUser()
{
    $requete = getBdd()->prepare("SELECT mdp FROM utilisateurs WHERE idUtilisateur = ?");
    $requete->execute([$_SESSION["idUtilisateur"]]);
    return $requete->fetch(PDO::FETCH_ASSOC);
}

function modifierMdpUtilisateur($newmdp)
{
    $newmdp = password_hash($newmdp, PASSWORD_BCRYPT);
    $requete = getBdd()->prepare("UPDATE utilisateurs SET mdp = ? WHERE idUtilisateur = ?");
    $requete->execute([$newmdp,$_SESSION["idUtilisateur"]]);
}

function recupererInfoUtilisateurs($idOrganisation)
{
    $requete = getBdd()->prepare("SELECT * FROM utilisateurs LEFT JOIN equipes USING(idEquipe) LEFT JOIN postes USING(idPoste) WHERE utilisateurs.idOrganisation = ?");
    $requete->execute([$idOrganisation]);
    return $requete->fetchAll(PDO::FETCH_ASSOC);
}

function recupChefEquipe($idChefEquipe)
{
    $requete = getBdd()->prepare("SELECT nom, prenom FROM utilisateurs WHERE idUtilisateur = ?");
    $requete->execute([$idChefEquipe]);
    return $requete->fetchAll(PDO::FETCH_ASSOC);
}

function recupInfosUtilisateur($idUser)
{
    $requete = getBdd()->prepare ("SELECT * FROM utilisateurs WHERE idUtilisateur = ?");
    $requete->execute($idUser);
    return $requete->fetch(PDO::FETCH_ASSOC);
}

function modifierEquipeUtilisateur($idEquipe, $idUser)
{
    $requete = getBdd()->prepare("UPDATE utilisateurs SET idEquipe = ? WHERE idUtilisateur = ?");
    $requete->execute([$idEquipe, $idUser]);
}

function modifierPosteUtilisateur($idPoste, $idUser)
{
    $requete = getBdd()->prepare("UPDATE utilisateurs SET idPoste = ? WHERE idUtilisateur = ?");
    $requete->execute([$idPoste, $idUser]);
}

function modifierNom($nom,$idUser)
{
    $requete = getBdd()->prepare("UPDATE utilisateurs SET nom = ? WHERE idUtilisateur = ?");
    $requete->execute([$nom,$idUser]);
}

function modifierPrenom($prenom,$idUser)
{
    $requete = getBdd()->prepare("UPDATE utilisateurs SET prenom = ? WHERE idUtilisateur = ?");
    $requete->execute([$prenom,$idUser]);
}

function verifEmailUtilisateur($email)
{
    $requete = getBdd()->prepare("SELECT email FROM utilisateurs WHERE email = ?");
    $requete->execute([$email]);

    if($requete->rowcount() > 0)
    {
        return true;
    } else {
        return false;
    }
}

function creerUtilisateur($nom,$prenom,$dateNaiss,$idPoste,$email,$idEquipe,$idOrganisation)
{
    try {

        $mdp = generateRandomString(6);
        $mdptemp = $mdp;
        $mdp = password_hash($mdp, PASSWORD_BCRYPT);

                        
        $requete = getBdd()->prepare("INSERT INTO utilisateurs (nom, prenom, dateNaiss, mdp, idPoste, email, idEquipe, idOrganisation)
        VALUES (?,?,?,?,?,?,?,?)");
        $requete->execute([$nom,$prenom,$dateNaiss,$mdp,$idPoste,$email,$idEquipe,$_SESSION["idOrganisation"]]);
        return $mdptemp;

    } catch (exception $e) {
        return false;
    }
}

function recupUtilisateurMail($mail)
{
    $requete = getBdd()->prepare("SELECT * FROM utilisateurs WHERE email = ?");
    $requete->execute([$mail]);
    return $requete->fetch(PDO::FETCH_ASSOC);
}


function recupUtilisateursEquipe($idEquipe)
{
    $requete = getBdd()->prepare("SELECT * FROM utilisateurs WHERE idEquipe = ?");
    $requete->execute([$idEquipe]);
    return $requete->fetchAll(PDO::FETCH_ASSOC);
}

function recupUtilisateursPoste($idPoste)
{
    $requete = getBdd()->prepare("SELECT * FROM utilisateurs WHERE idPoste = ?");
    $requete->execute([$idPoste]);
    return $requete->fetchAll(PDO::FETCH_ASSOC);
}

function recupererPosteUtilisateur($idUtilisateur)
{
    $requete = getBdd()->prepare("SELECT postes.nomPoste as nomPoste FROM utilisateurs INNER JOIN postes USING(idPoste) WHERE idUtilisateur = ?");
    $requete->execute([$idUtilisateur]);
    return $requete->fetch(PDO::FETCH_ASSOC);
}

function recupIdChefProjet($nomChef, $prenomChef)
{
    $requete = getBdd()->prepare("SELECT idUtilisateur FROM utilisateurs WHERE nom = ? AND prenom = ?");
    $requete->execute([$nomChef, $prenomChef]);
    return $requete->fetch(PDO::FETCH_ASSOC);
}