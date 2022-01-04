<?php
class Team extends Modele
{
    private $id;
    private $name;
    private $idOrganization;
    private $idProject;
    private $members = array();
    private $mapColumns = array();
    private $active;

    public function __construct($idTeam = null)
    {
        if($idTeam != null)
        {
            $sql = "SELECT *"; 
            $sql .= " FROM teams"; 
            $sql .= " WHERE rowid = ?";
            
            $requete = $this->getBdd()->prepare($sql);
            $requete->execute([$idTeam]);

            $Team = $requete->fetch(PDO::FETCH_OBJ);

            $this->id = $idTeam;
            $this->name = $Team->name;
            $this->idOrganization = $Team->fk_organization;
            $this->idProject = $Team->fk_project;
            $this->active = $Team->active;

            $sql = "SELECT u.rowid, u.lastname, u.firstname, u.birth, u.password, u.email, u.fk_organization";
            $sql .= " FROM users AS u";
            $sql .= " LEFT JOIN belong_to AS b ON u.rowid = b.fk_user";
            $sql .= " WHERE b.fk_team = ?";

            $requete = $this->getBdd()->prepare($sql);
            $requete->execute([$this->id]);

            $members = $requete->fetchAll(PDO::FETCH_OBJ);

            foreach($members as $member)
            {
                $obj = new User($member->rowid);
                $this->members[] = $obj;
            }

            $MapColumn = new MapColumn();

            $lines = $MapColumn->fetchAll($this->id);
            
            foreach($lines as $line)
            {
                $obj = new MapColumn($line->rowid);
                $this->mapColumns[] = $obj;
            }
        }
    }


    //! SETTER
    
    public function setName($name)
    {
        $this->name = $name;
    }

    public function setIdProject($idProject)
    {
        $this->idProject = $idProject;
    }

    public function setMembers(array $members)
    {
        $this->members = $members;
    }

    public function setMapColumns(array $mapColumns)
    {
        $this->mapColumns = $mapColumns;
    }

    public function setActive(bool $active)
    {
        $this->active = $active;
    }

    //! GETTER

    public function getName()
    {
        return $this->name;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getIdProject()
    {
        return $this->idProject;
    }

    public function getIdorganization()
    {
        return $this->idorganization;
    }

    public function getMembers()
    {
        return $this->members;
    }

    //* outdated, use instead count($this->members)
    public function countMembers()
    {
        return count($this->members);
    }

    public function getMapColumns()
    {
        return $this->mapColumns;
    }

    public function getActive()
    {
        return $this->active;
    }

    //! FETCH

    public function fetchAll($fk_project = null)
    {
        $fk_project = $fk_project == null ? $this->idProject : $fk_project;

        $sql = "SELECT *"; 
        $sql .= " FROM teams"; 
        $sql .= " WHERE fk_project = ?";
        
        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$fk_project]);

        return $requete->fetchAll(PDO::FETCH_OBJ);
    }

    public function fetch(int $idTeam)
    {
        $sql = "SELECT *"; 
        $sql .= " FROM teams"; 
        $sql .= " WHERE rowid = ?";
        
        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$idTeam]);

        $Team = $requete->fetch(PDO::FETCH_OBJ);

        $this->id = $idTeam;
        $this->name = $Team->name;
        $this->idOrganization = $Team->fk_organization;
        $this->idProject = $Team->fk_project;
        $this->active = $Team->active;

        $sql = "SELECT u.rowid, u.lastname, u.firstname, u.birth, u.password, u.email, u.fk_organization";
        $sql .= " FROM users AS u";
        $sql .= " LEFT JOIN belong_to AS b ON u.rowid = b.fk_user";
        $sql .= " WHERE b.fk_team = ?";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$this->id]);

        $members = $requete->fetchAll(PDO::FETCH_OBJ);

        foreach($members as $member)
        {
            $obj = new User($member->rowid);
            $this->members[] = $obj;
        }

        $MapColumn = new MapColumn();

        $lines = $MapColumn->fetchAll($this->id);
        
        foreach($lines as $line)
        {
            $obj = new MapColumn($line->rowid);
            $this->mapColumns[] = $obj;
        }
    }

    public function fetchNameByUserIdAndProjectId($idUser, $idProject)
    {
        $sql = "SELECT t.name";
        $sql .= " FROM teams AS t";
        $sql .= " LEFT JOIN work_to as w ON t.rowid = w.fk_team";
        $sql .= " LEFT JOIN projects as p ON w.fk_project = p.rowid";
        $sql .= " LEFT JOIN belong_to as b ON w.fk_team = b.rowid";
        $sql .= " LEFT JOIN users as u ON b.fk_user = u.rowid";
        $sql .= " WHERE u.rowid = ? AND p.rowid = ?";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$idUser, $idProject]);

        return $requete->fetch(PDO::FETCH_OBJ);
    }

    public function fetchByTeamIds(Array $teamIds)
    {
        $teamIds = implode("', '", $teamIds);

        $sql = "SELECT t.rowid, t.name, t.fk_organization, t.fk_project, t.active";
        $sql .= " FROM teams AS t";
        $sql .= " WHERE rowid IN ('$teamIds')";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute();

        return $requete->fetchAll(PDO::FETCH_OBJ);
    }

    //* outdated, only used in detailsProjet, use instead $Project->getTeams()
    public function fetchByProjectId($fk_project)
    {
        $sql = "SELECT t.rowid, t.name, t.fk_organization, t.fk_project, t.active";
        $sql .= " FROM teams AS t";
        $sql .= " WHERE t.fk_project = ?";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$fk_project]);

        return $requete->fetchAll(PDO::FETCH_OBJ);
    }

    public function fetchMaxId()
    {
        $sql = "SELECT MAX(rowid) AS rowid";
        $sql .= " FROM teams";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute();

        return $requete->fetch(PDO::FETCH_OBJ);
    }

    //! INSERT

    public function create(string $name, $fk_organization, $fk_project)
    {
        $status = array();

        $sql = "INSERT INTO teams (name, fk_organization, fk_project, active)";
        $sql .= " VALUES (?,?,?,?)";

        $requete = $this->getBdd()->prepare($sql);
        $status[] = $requete->execute([$name, $fk_organization, $fk_project, 1]);

        // get fk_team
        $sql = "SELECT MAX(rowid) AS rowid FROM teams";
        $requete = $this->getBdd()->prepare($sql);
        $requete->execute();
        $fk_team = $requete->fetch(PDO::FETCH_OBJ)->rowid;

        $sql = "INSERT INTO map_columns (name, fk_team, rank)";
        $sql .= " VALUES ('Open', ?, 0),('Ready', ?, 1),('In progress', ?, 2),('Closed', ?, 3)";

        $requete = $this->getBdd()->prepare($sql);
        $status[] = $requete->execute([$fk_team, $fk_team, $fk_team, $fk_team]);

        if(in_array(false, $status))
        {
            return false;
        }
        else
        {
            return true;
        }
    } 


    //! UPDATE

    public function updateName($name, $teamId = null)
    {
        $teamId == null ? $teamId = $this->id : $teamId;

        $sql = "UPDATE teams";
        $sql .= " SET name = ?";
        $sql .= " WHERE rowid = ?";

        $requete = $this->getBdd()->prepare($sql);
        return $requete->execute([$name, $teamId]);
    }

    public function updateIdProject($idProject)
    {
        $sql = "UPDATE teams"; 
        $sql .= " SET fk_project = ?"; 
        $sql .= " WHERE rowid = ?";

        $requete = $this->getBdd()->prepare($sql);
        return $requete->execute([$idProject, $this->id]);
    }

    public function updateActive($active, $teamId = null)
    {
        $teamId == null ? $teamId = $this->id : $teamId;

        $sql = "UPDATE teams";
        $sql .= " SET active = ?"; 
        $sql .= " WHERE rowid = ?";

        $requete = $this->getBdd()->prepare($sql);
        return $requete->execute([$active, $teamId]);
    }


    // DELETE

    public function delete($teamId = null)
    {
        $teamId = $teamId == null ? $this->id : $teamId;

        $sql = "DELETE FROM teams WHERE rowid = ?;";
        $sql .= "DELETE FROM belong_to WHERE fk_team = ?;";
        $sql .= "DELETE FROM tasks_comments WHERE fk_task IN (SELECT rowid FROM tasks WHERE fk_column IN (SELECT rowid FROM map_columns WHERE fk_team = ?));";
        $sql .= "DELETE FROM tasks_members WHERE fk_task IN (SELECT rowid FROM tasks WHERE fk_column IN (SELECT rowid FROM map_columns WHERE fk_team = ?));";
        $sql .= "DELETE FROM tasks WHERE fk_column IN (SELECT rowid FROM map_columns WHERE fk_team = ?);";
        $sql .= "DELETE FROM map_columns WHERE fk_team = ?;";
        
        $requete = $this->getBdd()->prepare($sql);
        return $requete->execute([$teamId,$teamId,$teamId,$teamId,$teamId,$teamId]);
    }
}