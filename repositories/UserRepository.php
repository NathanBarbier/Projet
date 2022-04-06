<?php

class UserRepository extends Repository
{
    public function fetchNextUsers(int $fk_organization = 0, int $offset = 0) 
    {
        if(is_int($offset) && is_int($fk_organization))
        {
            $sql = "SELECT rowid, lastname, firstname, email, fk_organization, admin";
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

    public function searchNonAdminUsersFromFirstnameAndLastname(int $fk_organization, string $query)
    {
        // split query terms
        $terms = explode(' ', $query);

        $sql = "SELECT rowid, lastname, firstname";
        $sql .= " FROM storieshelper_user";
        $sql .= " WHERE fk_organization = :fk_organization";

        $params = array();

        foreach($terms as $key => $term)
        {
            $sql .= ' AND ';

            $paramOne   = ':lastname'.$key;
            $paramTwo   = ':firstname'.$key;

            $sql .= "(lastname LIKE $paramOne OR firstname LIKE $paramTwo)";
            
            $params[$paramOne]   = "%$term%"; 
            $params[$paramTwo]   = "%$term%";
        }

        $requete = $this->getBdd()->prepare($sql);
        
        foreach($params as $name => $param)
        {
            $requete->bindParam($name, $param, PDO::PARAM_STR);
        }

        $requete->bindParam(':fk_organization', $fk_organization, PDO::PARAM_INT);

        $requete->execute();

        if($requete->rowCount() > 0)
        {
            return $requete->fetchAll(PDO::FETCH_OBJ);
        }
    }

    public function search(int $fk_organization, string $query)
    {
        // split query terms
        $terms = explode(' ', $query);

        $sql = "SELECT rowid, lastname, firstname, email, admin";
        $sql .= " FROM storieshelper_user";
        $sql .= " WHERE fk_organization = :fk_organization";

        $params = array();

        foreach($terms as $key => $term)
        {
            $sql .= ' AND ';

            if(strpos('administrateur', strtolower($term)) !== false)
            {
                $sql .= "admin = 1";
            }
            elseif(strpos('utilisateur', strtolower($term)) !== false)
            {
                $sql .= "admin = 0";
            }
            else
            {
                $paramOne   = ':lastname'.$key;
                $paramTwo   = ':firstname'.$key;
                $paramThree = ':email'.$key;

                $sql .= "(lastname LIKE $paramOne OR firstname LIKE $paramTwo OR email LIKE $paramThree)";
                
                $params[$paramOne]   = "%$term%"; 
                $params[$paramTwo]   = "%$term%"; 
                $params[$paramThree] = "%$term%"; 
            }
        }

        $requete = $this->getBdd()->prepare($sql);
        
        foreach($params as $name => $param)
        {
            $requete->bindParam($name, $param, PDO::PARAM_STR);
        }

        $requete->bindParam(':fk_organization', $fk_organization, PDO::PARAM_INT);

        $requete->execute();

        if($requete->rowCount() > 0)
        {
            return $requete->fetchAll(PDO::FETCH_OBJ);
        }
    }

    /**
     * @return boolean true if the user belongs to the organization | false otherwise
     */
    public function checkIfUserBelongsToOrganization(int $fk_organization, int $fk_user)
    {
        if(is_int($fk_organization) && is_int($fk_user))
        {
            $sql = "SELECT * FROM storieshelper_user";
            $sql .= " WHERE fk_organization = ? AND rowid = ?";

            $requete = $this->getBdd()->prepare($sql);
            $requete->execute([$fk_organization, $fk_user]);

            if($requete->rowCount() > 0)
            {
                return true;
            }
            else
            {
                return false;
            }
        }
        else
        {
            return false;
        }
    }
}

?>