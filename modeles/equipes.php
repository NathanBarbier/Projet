<?php

function recupererEquipes($idOrganisation)
{
    $requete = getBdd()->prepare("SELECT * FROM equipes WHERE idOrganisation = ?");
    $requete->execute([$idOrganisation]);
    return $requete->fetchAll(PDO::FETCH_ASSOC);
}
function recupererEquipe($idOrganisation, $idEquipe)
{
    $requete = getBdd()->prepare("SELECT * FROM equipes WHERE idOrganisation = ? and idEquipe = ?");
    $requete->execute([$idOrganisation, $idEquipe]);
    return $requete->fetch(PDO::FETCH_ASSOC);
}

function recupererMaxMinIdEquipes($idOrganisation)
{
    $requete = getBdd()->prepare("SELECT max(idEquipe) as MaxId, min(idEquipe) as MinId FROM equipes WHERE idOrganisation = ?");
    $requete->execute([$idOrganisation]);
    return $requete->fetch(PDO::FETCH_ASSOC);
}

function recupererNombreMembreParEquipe($idOrganisation)
{
    $requete = getBdd()->prepare("SELECT idEquipe, count(utilisateurs.idEquipe) as UtilisateursParEquipe FROM equipes left join utilisateurs using(idEquipe) where equipes.idOrganisation = ? group by equipes.idEquipe");
    $requete->execute([$idOrganisation]);
    return $requete->fetchAll(PDO::FETCH_ASSOC);
}

function recupererNombreMembreEquipe($idOrganisation, $idEquipe)
{
    $requete = getBdd()->prepare("SELECT idEquipe, count(utilisateurs.idEquipe) as UtilisateursEquipe FROM equipes left join utilisateurs using(idEquipe) where equipes.idOrganisation = ? and idEquipe = ?");
    $requete->execute([$idOrganisation, $idEquipe]);
    return $requete->fetchAll(PDO::FETCH_ASSOC);
}

function ajouterEquipe($ajoutEquipe, $idOrganisation)
{
    $requete = getBdd()->prepare("INSERT INTO equipes (nomEquipe, idOrganisation) VALUES (?,?)");
    $requete->execute([$ajoutEquipe, $idOrganisation]);
}

function recupChefEquipe($idEquipe)
{
    $requete = getBdd()->prepare("SELECT utilisateurs.nom, utilisateurs.prenom, utilisateurs.email FROM equipes INNER JOIN utilisateurs ON utilisateurs.idUtilisateur = equipes.chefEquipe WHERE equipes.idEquipe = ?");
    $requete->execute([$idEquipe]);
    return $requete->fetch(PDO::FETCH_ASSOC);
}

function recupChefEquipesParOrganisation($idOrganisation)
{
    $requete = getBdd()->prepare("SELECT utilisateurs.nom, utilisateurs.prenom, utilisateurs.email, chefEquipe, utilisateurs.idUtilisateur FROM equipes LEFT JOIN utilisateurs ON utilisateurs.idUtilisateur = equipes.chefEquipe WHERE equipes.idOrganisation = ?");
    $requete->execute([$idOrganisation]);
    return $requete->fetchAll(PDO::FETCH_ASSOC);
}