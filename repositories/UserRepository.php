<?php

class UserRepository extends Repository
{
    public function fetchNextUsers(int $fk_organization = 0, int $offset = 0) 
    {
        if(is_int($offset) && is_int($fk_organization))
        {
            $sql = "SELECT lastname, firstname, email, fk_organization, admin";
            $sql .= " FROM storieshelper_user";
            $sql .= " WHERE fk_organization = $fk_organization";
            $sql .= " ORDER BY `lastname`, `firstname`";
            $sql .= " LIMIT 30";
            $sql .= " OFFSET $offset";
    
            $requete = $this->getBdd()->prepare($sql);
            $requete->execute();
    
            if($requete->rowCount() > 0)
            {
                return $requete->fetchAll(PDO::FETCH_OBJ);
            }
        }
    }
}

?>