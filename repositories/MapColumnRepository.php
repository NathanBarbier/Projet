<?php 

class MapColumnRepository extends Repository
{
    public function fetchRandomFk_column(int $teamId, int $randomInt)
    {
        if(is_int($teamId) && is_int($randomInt))
        {
            $names = ['Open', 'Ready', 'In progress', 'Closed'];
            $name = $names[$randomInt];

            $sql = "SELECT rowid FROM storieshelper_map_column";
            $sql .= " WHERE fk_team = $teamId AND name = '$name'";

            $requete = $this->getBdd()->prepare($sql);
            $requete->execute();

            if($requete->rowCount() > 0)
            {
                $obj = $requete->fetch(PDO::FETCH_OBJ);

                return intval($obj->rowid);
            }
        }
    }
}

?>