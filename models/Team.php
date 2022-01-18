<?php
class Team extends Modele
{
    protected $rowid;
    protected $name;
    // private $Organization;
    // private $Project;
    protected $fk_project;
    protected $members = array();
    protected $mapColumns = array();
    protected $active;

    public function __construct($rowid = null)
    {
        if($rowid != null)
        {
            $this->fetch($rowid);
        }
    }


    // SETTER
    
    public function setRowid($rowid)
    {
        $this->rowid = $rowid;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    // public function setProject(Project $Project)
    // {
    //     $this->Project = $Project;
    // }
    public function setFk_project(int $fk_project)
    {
        $this->fk_project = $fk_project;
    }

    public function setMembers(array $members)
    {
        $this->members = $members;
    }

    public function setMapColumns(array $mapColumns)
    {
        $this->mapColumns = $mapColumns;
    }

    public function setActive(int $active)
    {
        $this->active = $active;
    }

    public function addUser(User $User)
    {
        $this->members[] = $User;
    }

    public function removeUser(int $fk_user)
    {
        $key = array_search($fk_user, array_column($this->object_to_array($this->members), 'rowid'));
        unset($this->members[$key]);
    }

    // GETTER

    public function getName()
    {
        return $this->name;
    }

    public function getRowid()
    {
        return $this->rowid;
    }

    // public function getProject()
    // {
    //     return $this->Project;
    // }
    public function getFk_project()
    {
        return $this->fk_project;
    }

    public function getOrganization()
    {
        return $this->Organization;
    }

    public function getMembers()
    {
        return $this->members;
    }

    public function getMapColumns()
    {
        return $this->mapColumns;
    }

    public function isActive()
    {
        return $this->active;
    }

    // FETCH

    public function fetch(int $rowid)
    {
        $sql = "SELECT *"; 
        $sql .= " FROM team";
        $sql .= " WHERE rowid = ?";
        
        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$rowid]);

        $obj = $requete->fetch(PDO::FETCH_OBJ);

        $this->rowid = $rowid;
        $this->name = $obj->name;
        // $this->Project = new Project($obj->fk_project);
        $this->fk_project = $obj->fk_project;
        $this->active = $obj->active;

        // fetch team members
        $sql = "SELECT u.rowid";
        $sql .= " FROM user AS u";
        $sql .= " LEFT JOIN belong_to AS b ON u.rowid = b.fk_user";
        $sql .= " WHERE b.fk_team = ?";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$this->rowid]);

        if($requete->rowCount() > 0)
        {
            $lines = $requete->fetchAll(PDO::FETCH_OBJ);

            foreach($lines as $line)
            {
                $this->members[] = new User($line->rowid);
            }
        }

        // fetch team / board columns
        $sql = "SELECT m.rowid";
        $sql .= " FROM map_column AS m";
        $sql .= " WHERE m.fk_team = ?";
        $sql .= " ORDER BY m.rank ASC";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$this->rowid]);

        if($requete->rowCount() > 0)
        {
            $lines = $requete->fetchAll(PDO::FETCH_OBJ);

            foreach($lines as $line)
            {
                $this->mapColumns[] = new MapColumn($line->rowid);        
            }
        }
    }

    public function fetchByTeamIds(Array $teamIds)
    {
        $teamIds = implode("', '", $teamIds);

        $sql = "SELECT t.rowid, t.name, t.fk_organization, t.fk_project, t.active";
        $sql .= " FROM team AS t";
        $sql .= " WHERE rowid IN ('$teamIds')";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute();

        return $requete->fetchAll(PDO::FETCH_OBJ);
    }

    //* outdated, only used in detailsProjet, use instead $Project->getTeams()
    public function fetchByProjectId($fk_project)
    {
        $sql = "SELECT t.rowid, t.name, t.fk_organization, t.fk_project, t.active";
        $sql .= " FROM team AS t";
        $sql .= " WHERE t.fk_project = ?";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$fk_project]);

        return $requete->fetchAll(PDO::FETCH_OBJ);
    }

    public function fetchMaxId()
    {
        $sql = "SELECT MAX(rowid) AS rowid";
        $sql .= " FROM team";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute();

        return $requete->fetch(PDO::FETCH_OBJ);
    }
    
    public function countTasksTodo()
    {
        $counter = 0;
        foreach($this->mapColumns as $MapColumn)
        {
            if($MapColumn->getName() == 'Ready')
            {
                $counter++;
            }
        }
    }

    public function countTasksInprogress()
    {
        $counter = 0;
        foreach($this->mapColumns as $MapColumn)
        {
            if($MapColumn->getName() == 'In progress')
            {
                $counter++;
            }
        }
    }


    // INSERT

    public function create(string $name = null, int $fk_project = null)
    {
        $name = $name == null ? $this->name : $name; 
        $fk_project = $fk_project == null ? $this->fk_project : $fk_project; 

        $sql = "INSERT INTO team (name, fk_project, active)";
        $sql .= " VALUES (?,?,?)";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$name, $fk_project, 1]);
        
        // get fk_team
        $sql = "SELECT MAX(rowid) AS rowid FROM team";
        $requete = $this->getBdd()->prepare($sql);
        $requete->execute();
        $fk_team = $requete->fetch(PDO::FETCH_OBJ)->rowid;

        $sql = "INSERT INTO map_column (name, fk_team, rank)";
        $sql .= " VALUES ('Open', ?, 0),('Ready', ?, 1),('In progress', ?, 2),('Closed', ?, 3)";

        $requete = $this->getBdd()->prepare($sql);
        return $requete->execute([$fk_team, $fk_team, $fk_team, $fk_team]);
    } 


    // UPDATE

    public function update()
    {
        $sql = "UDPATE team";
        $sql .= " SET";
        $sql .= " name = $this->name";
        $sql .= " ,fk_project = $this->fk_project";
        $sql .= " ,active = $this->active";
        $sql .= " WHERE rowid = $this->rowid";

        $requete = $this->getBdd()->prepare($sql);
        return $requete->execute();
    }

    public function updateName($name, $teamId = null)
    {
        $teamId == null ? $teamId = $this->rowid : $teamId;

        $sql = "UPDATE team";
        $sql .= " SET name = ?";
        $sql .= " WHERE rowid = ?";

        $requete = $this->getBdd()->prepare($sql);
        return $requete->execute([$name, $teamId]);
    }

    public function updateIdProject($idProject)
    {
        $sql = "UPDATE team"; 
        $sql .= " SET fk_project = ?"; 
        $sql .= " WHERE rowid = ?";

        $requete = $this->getBdd()->prepare($sql);
        return $requete->execute([$idProject, $this->rowid]);
    }

    public function updateActive($active, $teamId = null)
    {
        $teamId == null ? $teamId = $this->rowid : $teamId;

        $sql = "UPDATE team";
        $sql .= " SET active = ?"; 
        $sql .= " WHERE rowid = ?";

        $requete = $this->getBdd()->prepare($sql);
        return $requete->execute([$active, $teamId]);
    }


    // DELETE

    public function delete($teamId = null)
    {
        $teamId = $teamId == null ? $this->rowid : $teamId;

        $sql = "DELETE FROM team WHERE rowid = ?;";
        $sql .= "DELETE FROM belong_to WHERE fk_team = ?;";
        $sql .= "DELETE FROM task_comment WHERE fk_task IN (SELECT rowid FROM tasks WHERE fk_column IN (SELECT rowid FROM map_column WHERE fk_team = ?));";
        $sql .= "DELETE FROM task_member WHERE fk_task IN (SELECT rowid FROM tasks WHERE fk_column IN (SELECT rowid FROM map_column WHERE fk_team = ?));";
        $sql .= "DELETE FROM tasks WHERE fk_column IN (SELECT rowid FROM map_column WHERE fk_team = ?);";
        $sql .= "DELETE FROM map_column WHERE fk_team = ?;";
        
        $requete = $this->getBdd()->prepare($sql);
        return $requete->execute([$teamId,$teamId,$teamId,$teamId,$teamId,$teamId]);
    }
}