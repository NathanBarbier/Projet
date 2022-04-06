<?php 

class TeamRepository extends Repository
{

    public function fetchAffectedTasksCount(int $fk_team, int $fk_user)
    {
        if(is_int($fk_team) && is_int($fk_user))
        {
            $sql = "SELECT COUNT(tm.fk_task) AS counter"; 
            $sql .= " FROM storieshelper_task_member AS tm";
            $sql .= " INNER JOIN storieshelper_task AS t ON t.rowid = tm.fk_task";
            $sql .= " WHERE tm.fk_user = $fk_user AND NOT t.active = 0";
    
            $requete = $this->getBdd()->prepare($sql);
            $requete->execute();
    
            if($requete->rowCount() > 0)
            {
                return $requete->fetch(PDO::FETCH_OBJ)->counter;
            }
        }
    }

    public function checkIfTeamBelongsToProject(int $fk_project, int $fk_team)
    {
        if(is_int($fk_project) && is_int($fk_team))
        {
            $sql = "SELECT * FROM storieshelper_team";
            $sql .= " WHERE fk_project = ? AND rowid = ?";
    
            $requete = $this->getBdd()->prepare($sql);
            $requete->execute([$fk_project, $fk_team]);

            if($requete->rowCount() > 0)
            {
                return true;
            }
            else
            {
                return false;
            }
        }
    }
}

?>