<?php

function recupererPostes($idOrganisation)
{
    $requete = getBdd()->prepare("SELECT * FROM postes WHERE idOrganisation = ?");
    $requete->execute([$idOrganisation]);
    return $requete->fetchAll(PDO::FETCH_ASSOC);
}

function recupIdPoste($idPoste)
{
    $requete = getBdd()->prepare("SELECT * FROM postes WHERE idPoste = ?");
    $requete->execute([$idPoste]);
    return $requete->fetch(PDO::FETCH_ASSOC);
}
function modifierPoste($modifier, $id)
{
    $requete = getBdd()->prepare("UPDATE postes SET nomPoste = ? WHERE idPoste = ?");
    $requete->execute([$modifier, $id]);
}
function supprPoste($suppr, $idOrganisation)
{
    $requete = getBdd()->prepare("DELETE FROM postes WHERE idPoste = ?");
    $requete->execute([$suppr]);

    $requete = getBdd()->prepare("SELECT idPoste FROM postes WHERE idOrganisation = ? LIMIT 1");
    $requete->execute([$idOrganisation]);
    $indefini = $requete->fetch(PDO::FETCH_ASSOC);

    $requete = getBdd()->prepare("UPDATE utilisateurs SET idPoste = ? WHERE idPoste = ?");
    $requete->execute([$indefini["idPoste"], $suppr]);
}
function recupererNombreMembreParPoste($idOrganisation)
{
    $requete = getBdd()->prepare("SELECT idPoste, count(utilisateurs.idPoste) as UtilisateursParPoste FROM postes LEFT join utilisateurs using(idPoste) where postes.idOrganisation = ? group by postes.idPoste");
    $requete->execute([$idOrganisation]);
    return $requete->fetchAll(PDO::FETCH_ASSOC);
}
function ajouterPoste($ajoutPoste, $idOrganisation)
{
    $requete = getBdd()->prepare("INSERT INTO postes (nomPoste, idOrganisation) VALUES (?,?)");
    $requete->execute([$ajoutPoste, $idOrganisation]);
}