<?php 

class ProjectRepository extends Repository
{
    public function fetchNextProjects(int $fk_organization = 0, int $offset = 0) 
    {
        if(is_int($offset) && is_int($fk_organization))
        {
            $sql = "SELECT rowid, name, type, active, fk_organization";
            $sql .= " FROM storieshelper_project";
            $sql .= " WHERE fk_organization = $fk_organization";
            $sql .= " ORDER BY name";
            $sql .= " LIMIT 10";
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