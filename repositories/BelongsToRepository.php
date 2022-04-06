<?php

class BelongsToRepository extends Repository
{
    public function checkIfUserBelongsToTeam(int $fk_user, int $fk_team)
    {
        if(is_int($fk_user) && is_int($fk_team))
        {
            $sql = "SELECT * FROM storieshelper_belong_to";
            $sql .= " WHERE fk_user = ? AND fk_team = ?";

            $requete = $this->getBdd()->prepare($sql);
            $requete->execute([$fk_user, $fk_team]);

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

    public function getTeamIdFromUserIdAndProjectId(int $fk_project, int $fk_user)
    {
        if(is_int($fk_project) && is_int($fk_user))
        {
            $sql = "SELECT t.rowid AS teamId";
            $sql .= " FROM storieshelper_belong_to AS bt";
            $sql .= " INNER JOIN storieshelper_team AS t ON t.rowid = bt.fk_team";
            $sql .= " WHERE t.fk_project = ?";
            $sql .= " AND bt.fk_user = ?";
    
            $requete = $this->getBdd()->prepare($sql);
            $requete->execute([$fk_project, $fk_user]);
    
            if($requete->rowCount() > 0)
            {
                $obj = $requete->fetch(PDO::FETCH_OBJ);
                return intval($obj->teamId);
            }
        }
    }

    public function fetchTeamMembersCount(int $fk_team)
    {
        if(is_int($fk_team))
        {
            $sql = "SELECT COUNT(DISTINCT(fk_user)) AS counter FROM storieshelper_belong_to";
            $sql .= " WHERE fk_team = ?";

            $requete = $this->getBdd()->prepare($sql);
            $requete->execute([$fk_team]);

            if($requete->rowCount() > 0)
            {
                $obj = $requete->fetch(PDO::FETCH_OBJ);
                return intval($obj->counter);
            }
        }
    }
}

?>