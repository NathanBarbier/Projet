<?php
class WorkTo extends Modele
{

    function __construct()
    {
        
    }

    // INSERT
    public function create($idEquipe, $idProjet)
    {
        $sql = "INSERT INTO travaille_sur (idEquipe, idProjet)"; 
        $sql.= " VALUES (?, ?)";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$idEquipe, $idProjet]);
    }
}

?>