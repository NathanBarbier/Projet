<?php 

class ProjectRepository extends Repository
{
    public function fetchNextProjects(int $fk_organization = 0, int $offset = 0) 
    {
        if(is_int($offset) && is_int($fk_organization))
        {
            // fetch projects
            $sql = "SELECT rowid, name, type, active, fk_organization";
            $sql .= " FROM storieshelper_project";
            $sql .= " WHERE fk_organization = $fk_organization";
            $sql .= " ORDER BY name";
            $sql .= " LIMIT 11"; // limit is set to 11 instead of 10 to check if there are remaining projects after the offset
            $sql .= " OFFSET $offset";
    
            $requete = $this->getBdd()->prepare($sql);
            $requete->execute();

            if($requete->rowCount() > 0)
            {
                return $requete->fetchAll(PDO::FETCH_OBJ);
            }
        }
    }

    public function fetchActiveProjectsCount(int $fk_organization)
    {
        $sql = "SELECT COUNT(*) AS counter FROM storieshelper_project";
        $sql .= " WHERE fk_organization = $fk_organization AND active = 1";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute();

        if($requete->rowCount() > 0)
        {
            return $requete->fetch(PDO::FETCH_OBJ)->counter;
        }
    }

    public function fetchArchivedProjectsCount(int $fk_organization)
    {
        $sql = "SELECT COUNT(*) AS counter FROM storieshelper_project";
        $sql .= " WHERE fk_organization = $fk_organization AND active = 0";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute();

        if($requete->rowCount() > 0)
        {
            return $requete->fetch(PDO::FETCH_OBJ)->counter;
        }
    }

    public function search(int $fk_organization, string $query)
    {
        // split query terms
        $terms = explode(' ', $query);

        $sql = "SELECT rowid, name, type, fk_organization, active";
        $sql .= " FROM storieshelper_project";
        $sql .= " WHERE fk_organization = :fk_organization";

        $params = array();

        foreach($terms as $key => $term)
        {
            $sql .= ' AND ';

            if(strpos('ouvert', strtolower($term)) !== false)
            {
                $sql .= "active = 1";
            }
            elseif(strpos('archivé', mb_strtolower($term, 'UTF-8')) !== false)
            {
                $sql .= "active = 0";
            }
            else
            {
                $paramOne = ':name'.$key;
                $paramTwo = ':type'.$key;

                $sql .= "(name LIKE $paramOne OR type LIKE $paramTwo)";
                
                $params[$paramOne] = "%$term%"; 
                $params[$paramTwo] = "%$term%"; 
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

    public function checkIfProjectBelongsToOrganization(int $fk_project, int $fk_organization)
    {
        if(is_int($fk_project) && is_int($fk_organization))
        {
            $sql = "SELECT * FROM storieshelper_project";
            $sql .= " WHERE rowid = ? AND fk_organization = ?";

            $requete = $this->getBdd()->prepare($sql);
            $requete->execute([$fk_project, $fk_organization]);

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