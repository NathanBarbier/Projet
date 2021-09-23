<?php
class WorkTo extends Modele
{
    private $teamId;
    private $projectId;

    function __construct($teamId = null)
    {
        if($teamId != null)
        {
            $sql = "SELECT fk_team, fk_project";
            $sql .= " FROM work_to";
            $sql .= " WHERE fk_team = ?";

            $requete = $this->getBdd()->prepare($sql);
            $requete->execute([$teamId]);

            $line = $requete->fetch(PDO::FETCH_OBJ);

            $this->teamId = $teamId;
            $this->projectId = $line->fk_project;
        }
    }


    // INSERT

    public function create($idTeam, $idProject)
    {
        $sql = "INSERT INTO work_to (idTeam, idProject)"; 
        $sql.= " VALUES (?, ?)";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$idTeam, $idProject]);
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


    // FETCH

    public function fetchByProjectId($projectId)
    {
        $sql = " SELECT w.fk_team, w.fk_project";
        $sql .= " FROM work_to AS w";
        $sql .= " WHERE fk_project = ?";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$projectId]);
        
        return $requete->fetchAll(PDO::FETCH_OBJ);
    }
}

?>