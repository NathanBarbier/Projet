<?php
function recupererClientOrganisation($idOrganisation)
{
    $requete = getBdd()->prepare("SELECT clients.nom FROM clients LEFT JOIN projets USING(idClient) LEFT JOIN travaille_sur USING(idProjet) LEFT JOIN equipes USING(idEquipe) WHERE equipes.idOrganisation = ?");
    $requete->execute([$idOrganisation]);
    return $requete->fetchAll(PDO::FETCH_ASSOC);
}

function verifClient($nomClient)
{
    $requete = getBdd()->prepare("SELECT * FROM clients WHERE nom = ?");
    $requete->execute([$nomClient]);
    if($requete->fetch(PDO::FETCH_ASSOC) > 0)
    {
        return true;
    } else {
        return false;
    }
}

function insertClient($client)
{
    $requete = getBdd()->prepare("INSERT INTO clients (nom) VALUES (?)");
    $requete->execute([$client]);
}

function recupIdClient($nomClient)
{
    $requete = getBdd()->prepare("SELECT idClient FROM clients WHERE nom = ?");
    $requete->execute([$nomClient]);
    return $requete->fetch(PDO::FETCH_ASSOC);
}
?>