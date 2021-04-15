<?php
function supprimerOrganisation()
{

    $requete = getBdd()->prepare("DELETE travaille_sur FROM travaille_sur INNER JOIN equipes USING(idEquipe) WHERE idOrganisation = ?");
    $requete->execute([$_SESSION["idOrganisation"]]);

    $requete = getBdd()->prepare("DELETE FROM clients  WHERE idOrganisation = ?");
    $requete->execute([$_SESSION["idOrganisation"]]);

    $requete = getBdd()->prepare("DELETE FROM projets WHERE idOrganisation = ?");
    $requete->execute([$_SESSION["idOrganisation"]]);

    $requete = getBdd()->prepare("DELETE FROM utilisateurs WHERE idOrganisation = ?");
    $requete->execute([$_SESSION["idOrganisation"]]);
    
    $requete = getBdd()->prepare("DELETE FROM equipes WHERE idOrganisation = ?");
    $requete->execute([$_SESSION["idOrganisation"]]);
    
    $requete = getBdd()->prepare("DELETE FROM postes WHERE idOrganisation = ?");
    $requete->execute([$_SESSION["idOrganisation"]]);
    
    $requete = getBdd()->prepare("DELETE FROM organisations WHERE idOrganisation = ?");
    $requete->execute([$_SESSION["idOrganisation"]]);

    // supprimer aussi les informations en rapport avec l'organisation
    session_destroy();

}

function recupererInfoOrganisation($idOrganisation)
{
    // Récuperer Nom organisation / email / nombre d'employés / nombre d'équipes
    $requete = getBdd()->prepare("SELECT organisations.nom AS nom, organisations.email AS email, count(equipes.idEquipe) AS nombreEquipes, count(utilisateurs.idUtilisateur) AS nombreEmployes FROM organisations INNER JOIN utilisateurs USING(idOrganisation) INNER JOIN equipes USING(idOrganisation) WHERE organisations.idOrganisation = ? ");
    $requete->execute([$idOrganisation]);
    return $requete->fetch(PDO::FETCH_ASSOC);
}

function recupNomOrganisation($organisation)
{
    $requete = getBdd()->prepare("SELECT Nom FROM organisations WHERE Nom = ?");
    $requete->execute([$organisation]);
    if($requete->rowCount() > 0)
    {
        return true;
    } else {
        return false;
    }
}

function recupEmailOrganisation($mail)
{
    $requete = getBdd()->prepare("SELECT Email FROM organisations WHERE Email = ?");
    $requete->execute([$mail]);
    if($requete->rowCount() > 0)
    {
        return true;
    } else {
        return false;
    }
}

function creerOrganisation($mail, $mdp, $organisation)
{
    $requete = getBdd()->prepare("INSERT INTO organisations(Email, Mdp, Nom)
    VALUES(?, ?, ?)");
    $requete->execute([$mail, $mdp, $organisation]);

    $requete = getBdd()->prepare("SELECT max(idOrganisation) AS maxId FROM organisations");
    $requete->execute();
    $idMax = $requete->fetch(PDO::FETCH_ASSOC);

    if(empty($idMax["maxId"]))
    {
        $idMax["maxId"] = 1;
    }
    
    $requete = getBdd()->prepare("INSERT INTO equipes (nomEquipe, idOrganisation) VALUES (?, ?);
    INSERT INTO postes (nomPoste, idOrganisation) VALUES (?, ?)");
    $requete->execute(["indéfini",$idMax["maxId"],"indéfini",$idMax["maxId"]]);
}

function recupOrganisationMail($mail)
{
    $requete = getBdd()->prepare("SELECT * FROM organisations WHERE email = ?");
    $requete->execute([$mail]);
    return $requete->fetch(PDO::FETCH_ASSOC);
}

function verifEmailOrganisation($email)
{
    $requete = getBdd()->prepare("SELECT email FROM organisations WHERE email = ?");
    $requete->execute([$email]);

    if($requete->rowcount() > 0)
    {
        return true;
    } else {
        return false;
    }
}
?>