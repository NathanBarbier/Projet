<?php
class WorkTo extends Modele
{

    function __construct()
    {
        
    }

    // INSERT
    public function create($idEquipe, $idProjet)
    {
        $requete = $this->getBdd()->prepare("INSERT INTO travaille_sur (idEquipe, idProjet) VALUES (?, ?)");
        $requete->execute([$idEquipe, $idProjet]);
    }
}

?>