<?php

function recupProjetParEquipe($idEquipe)
{
    $requete = getBdd()->prepare("SELECT equipes.nomEquipe, projets.nom, projets.type from travaille_sur inner join equipes using(idEquipe) inner join projets using(idProjet) where idEquipe = ?");
    $requete->execute([$idEquipe]);
    return $requete->fetchAll(PDO::FETCH_ASSOC);
} 