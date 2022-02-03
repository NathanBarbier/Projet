<?php

Class Project extends Modele
{
    public $rowid;
    protected $name;
    protected $type;
    protected $open;
    protected $description;
    // private $Organization;
    protected $fk_organization;
    protected $teams = array();
    protected $active;

    public function __construct($rowid = null)
    {
        if($rowid !== null)
        {
           $this->fetch($rowid);
        }
    }

    // GETTER
    public function getRowid()
    {
        return $this->rowid;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getOpen()
    {
        return $this->open;
    }

    // public function getOrganization()
    // {
    //     return $this->Organization;
    // }
    public function getFk_organization()
    {
        return $this->fk_organization;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getTeams()
    {
        return $this->teams;
    }

    public function isActive()
    {
        return $this->active;
    }

    // SETTER

    public function setRowid(int $rowid)
    {
        $this->rowid = $rowid;
    }

    public function setName(string $name)
    {
        $this->name = $name;
    }

    public function setDescription(string $description)
    {
        $this->description = $description;
    }

    public function setType(string $type)
    {
        $this->type = $type;
    }

    public function setTeams(array $teams)
    {
        $this->teams[] = $teams;
    }

    // public function setOrganization(Organization $Organization)
    // {
    //     $this->Organization = $Organization;
    // }
    public function setFk_organization(int $fk_organization)
    {
        $this->fk_organization = $fk_organization;
    }

    public function setActive($active)
    {
        $this->active = $active;
    }

    public function addTeam(Team $Team)
    {
        $this->teams[] = $Team;
    }

    public function removeTeam(int $fk_team)
    {
        foreach($this->teams as $key => $Team)
        {
            if($Team->getRowid() == $fk_team)
            {
                unset($this->teams[$key]);
            }
        }
    }


    // UPDATE

    public function update()
    {
        $sql = "UPDATE project";
        $sql .= " SET";
        $sql .= " name = ?";
        $sql .= " ,type = ?";
        $sql .= " ,open = ?";
        $sql .= " ,fk_organization = ?";
        $sql .= " ,description = ?";
        $sql .= " ,active = ?";
        $sql .= " WHERE rowid = ?";

        $requete = $this->getBdd()->prepare($sql);
        return $requete->execute([$this->name,$this->type,$this->open,$this->fk_organization,$this->description,$this->active,$this->rowid]);
    }


    // FETCH

    public function fetch(int $rowid)
    {
        $sql = "SELECT p.rowid, p.name, p.type, p.open, p.fk_organization, p.description, p.active";
        $sql .= " FROM project AS p";
        $sql .= " WHERE p.rowid = ?";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$rowid]);

        if($requete->rowCount() > 0)
        {
            $obj = $requete->fetch(PDO::FETCH_OBJ);

            $this->rowid = $rowid;
            $this->name = $obj->name;
            $this->type = $obj->type;
            $this->open = $obj->open;
            $this->description = $obj->description;
            $this->active = $obj->active;
            $this->fk_organization = $obj->fk_organization;
            
            $this->fetchTeams();
            // $this->fetchOrganization();
        }
    }

    public function fetchByTeam($fk_team)
    {
        $sql = "SELECT fk_project";
        $sql .= " FROM team";
        $sql .= " WHERE rowid = ?";

        $requete = $this->getBdd()->prepare($sql);
        $status = $requete->execute([$fk_team]);

        if($status)
        {
            $obj = $requete->fetch(PDO::FETCH_OBJ);
            $this->fetch($obj->fk_project);
        }
    }

    public function fetchTeams()
    {
        $sql = "SELECT t.rowid"; 
        $sql .= " FROM team as t"; 
        $sql .= " WHERE fk_project = ?";
        
        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$this->rowid]);

        if($requete->rowCount() > 0)
        {
            $lines = $requete->fetchAll(PDO::FETCH_OBJ);
            
            foreach($lines as $line)
            {
                $this->teams[] = new Team($line->rowid);
            }
        }
    }

    public function fetchOrganization()
    {
        $sql = "SELECT p.fk_organization";
        $sql .= " FROM project AS p";
        $sql .= " WHERE p.rowid = ?";

        $requete = $this->getBdd()->prepare($sql);
        $status = $requete->execute([$this->rowid]);

        if($status)
        {
            $obj = $requete->fetch(PDO::FETCH_OBJ);
            $this->Organization = new Organization($obj->fk_organization);
        }
    }

    public function fetchMaxId()
    {
        $sql = "SELECT max(rowid) AS maxId";
        $sql .=" FROM project";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute();

        return $requete->fetch(PDO::FETCH_OBJ);
    }


    // INSERT
    
    /** Create object in db
     * 
     */
    public function create()
    {
        $sql = "INSERT INTO project (name, type, open, description, fk_organization, active)";
        $sql .= " VALUES (?,?,NOW(),?,?,1)";

        $requete = $this->getBdd()->prepare($sql);

        return $requete->execute([$this->name, $this->type, $this->description, $this->fk_organization]);
    }

    // delete

    public function delete()
    {
        // delete task comments
        $sql = "DELETE FROM task_comment WHERE fk_task IN(SELECT rowid FROM task WHERE fk_column IN(SELECT rowid FROM map_column WHERE fk_team IN(SELECT rowid FROM team WHERE fk_project = ?)))";

        // delete task member
        $sql .= "DELETE FROM task_member WHERE fk_task IN(SELECT rowid FROM task WHERE fk_column IN(SELECT rowid FROM map_column WHERE fk_team IN(SELECT rowid FROM team WHERE fk_project = ?)))";

        // delete tasks
        $sql .= "DELETE FROM task WHERE fk_column IN(SELECT rowid FROM map_column WHERE fk_team IN(SELECT rowid FROM team WHERE fk_project = ?))";

        // delete all map columns
        $sql .= "DELETE FROM map_column WHERE fk_team IN(SELECT rowid FROM team WHERE fk_project = ?)";

        // delete all belongs_to
        $sql .= "DELETE FROM belong_to WHERE fk_team IN (SELECT rowid FROM team WHERE fk_project = ?)";

        // delete all teams
        $sql .= "DELETE FROM team WHERE fk_project = ?";

        // delete project
        $sql .= "DELETE FROM project WHERE rowid = ?";
        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$this->rowid,$this->rowid,$this->rowid,$this->rowid,$this->rowid,$this->rowid,$this->rowid]);
    }
}