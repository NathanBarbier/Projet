<?php

function ajoutEquipeAuProjet($idEquipe, $idProjet)
{
    $requete = getBdd()->prepare("INSERT INTO travaille_sur (idEquipe, idProjet) VALUES (?, ?)");
    $requete->execute([$idEquipe, $idProjet]);
}

?>