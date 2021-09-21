<?php
class WorkTo extends Modele
{
    private $teamId;
    private $projectId;

    function __construct($teamId = null)
    {
        if($teamId != null)
        {
            $sql = "SELECT idEquipe, idProjet";
            $sql .= " FROM travaille_sur";
            $sql .= " WHERE idEquipe = ?";

            $requete = $this->getBdd()->prepare($sql);
            $requete->execute([$teamId]);

            $line = $requete->fetch(PDO::FETCH_OBJ);

            $this->teamId = $teamId;
            $this->projectId = $line->idProjet;
        }
    }

    // INSERT
    public function create($idEquipe, $idProjet)
    {
        $sql = "INSERT INTO travaille_sur (idEquipe, idProjet)"; 
        $sql.= " VALUES (?, ?)";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$idEquipe, $idProjet]);
    }

    
    // GETTER

    public function getTeamId()
    {
        return $this->teamId;
    }

    public function getProjectId()
    {
        return $this->projectId;
    }
}

?>