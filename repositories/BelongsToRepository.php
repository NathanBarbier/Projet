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
}

?>