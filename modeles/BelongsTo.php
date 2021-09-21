<?php
class BelongsTo extends Modele
{
    private $userIds = array();
    private $teamIds = array();

    function __construct($idUser = null)
    {
        if($idUser != null)
        {
            $sql = "SELECT fk_user, fk_equipe";
            $sql .= " FROM appartient_a";
            $sql .= " WHERE fk_user = ?";

            $requete = $this->getBdd()->prepare($sql);
            $requete->execute([$idUser]);

            $lines = $requete->fetchAll(PDO::FETCH_OBJ);

            foreach($lines as $line)
            {
                $this->userIds[] = $line->fk_user;
                $this->teamIds[] = $line->fk_equipe;
            }
        }
    }

    // INSERT
    public function create($idUser, $idEquipe)
    {
        $sql = "INSERT INTO appartient_a (fk_user, fk_equipe)"; 
        $sql.= " VALUES (?, ?)";

        $requete = $this->getBdd()->prepare($sql);
        return $requete->execute([$idUser, $idEquipe]);
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