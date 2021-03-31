<?php

function recupererEquipes($idOrganisation)
{
    $requete = getBdd()->prepare("SELECT * FROM equipes WHERE idOrganisation = ?");
    $requete->execute([$idOrganisation]);
    return $requete->fetchAll(PDO::FETCH_ASSOC);
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

function ajouterEquipe($ajoutEquipe, $idOrganisation)
{
    $requete = getBdd()->prepare("INSERT INTO equipes (nomEquipe, idOrganisation) VALUES (?,?)");
    $requete->execute([$ajoutEquipe, $idOrganisation]);
}