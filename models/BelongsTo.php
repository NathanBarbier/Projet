<?php
class BelongsTo extends Modele
{
    private $fk_user;
    private $fk_team;

    function __construct($fk_user = null, $fk_team = null)
    {
        if($fk_user != null && $fk_team != null)
        {
            $sql = "SELECT fk_user, fk_team";
            $sql .= " FROM belong_to";
            $sql .= " WHERE fk_user = ? AND fk_team";

            $requete = $this->getBdd()->prepare($sql);
            $requete->execute([$fk_user, $fk_team]);

            $result = $requete->fetch(PDO::FETCH_OBJ);

            if($result)
            {
                $this->userId = $result->fk_user;
                $this->teamId = $result->fk_team;
            }
        }
    }
    
    // GETTER
    
    public function getFk_user()
    {
        return $this->fk_user;
    }
    
    public function getFk_team()
    {
        return $this->fk_team;
    }

    // SETTER

    public function setFk_user(int $fk_user)
    {
        $this->fk_user = $fk_user;
    }

    public function setFk_team(int $fk_team)
    {
        $this->fk_team = $fk_team;
    }
    
    // INSERT

    public function create($fk_user, $fk_team)
    {
        $sql = "INSERT INTO belong_to (fk_user, fk_team)"; 
        $sql.= " VALUES (?, ?)";

        $requete = $this->getBdd()->prepare($sql);
        return $requete->execute([$fk_user, $fk_team]);
    }

    // DELETE

    public function delete($fk_user, $fk_team)
    {
        $sql = "DELETE FROM belong_to";
        $sql .= " WHERE fk_user = ? AND fk_team = ?";

        $requete = $this->getBdd()->prepare($sql);
        return $requete->execute([$fk_user, $fk_team]);
    }

    // METHODS

    /** Return all associated teams with the user
     * @param int $fk_user The user for which we will search for the related teams
     * @return Array<BelongTo> All associated teams with the user
     */
    public function fetchAll(int $fk_user)
    {
        $sql = "SELECT fk_user, fk_team";
        $sql .= " FROM belong_to";
        $sql .= " WHERE fk_user = ?";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$fk_user]);

        $lines = $requete->fetchAll(PDO::FETCH_OBJ);

        $BelongsTos = array();

        foreach($lines as $line)
        {
            $BelongsTos[] = new BelongsTo($line->rowid);
        }

        return $BelongsTos;
    }
}

?>