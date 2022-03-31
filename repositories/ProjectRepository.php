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
}

?>