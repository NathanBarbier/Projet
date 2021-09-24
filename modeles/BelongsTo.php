<?php
class BelongsTo extends Modele
{
    private $userIds = array();
    private $teamIds = array();

    function __construct($fk_user = null)
    {
        if($fk_user != null)
        {
            $sql = "SELECT fk_user, fk_team";
            $sql .= " FROM belong_to";
            $sql .= " WHERE fk_user = ?";

            $requete = $this->getBdd()->prepare($sql);
            $requete->execute([$fk_user]);

            $lines = $requete->fetchAll(PDO::FETCH_OBJ);

            foreach($lines as $line)
            {
                $this->userIds[] = $line->fk_user;
                $this->teamIds[] = $line->fk_team;
            }
        }
    }

    // INSERT
    public function create($fk_user, $fk_team)
    {
        $sql = "INSERT INTO belong_to (fk_user, fk_team)"; 
        $sql.= " VALUES (?, ?)";

        $requete = $this->getBdd()->prepare($sql);
        return $requete->execute([$fk_user, $fk_team]);

    }

    // GETTER
    public function getUserIds()
    {
        return $this->userIds;
    }

    public function getTeamIds()
    {
        return $this->teamIds;
    }
}

?>