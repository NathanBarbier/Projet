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
        $sql = "UDPATE project";
        $sql .= " SET";
        $sql .= " name = $this->name";
        $sql .= " ,type = $this->type";
        $sql .= " ,open = $this->open";
        $sql .= " ,fk_organization = $this->fk_organization";
        $sql .= " ,description = $this->description";
        $sql .= " ,active = $this->active";
        $sql .= " WHERE rowid = $this->rowid";

        $requete = $this->getBdd()->prepare($sql);
        return $requete->execute();
    }

    public function updateName($name)
    {
        $sql = "UPDATE project";
        $sql .= " SET name = ?"; 
        $sql .= " WHERE rowid =  ?";

        $requete = $this->getBdd()->prepare($sql);
        return $requete->execute([$name, $this->rowid]);
    }

    public function updateType(string $type)
    {
        $sql = "UPDATE project";
        $sql .= " SET type = ?";
        $sql .= " WHERE rowid =  ?";

        $requete = $this->getBdd()->prepare($sql);
        return $requete->execute([$type, $this->rowid]);
    }

    public function updateDescription(string $description)
    {
        $sql = "UPDATE project";
        $sql .= " SET description = ?";
        $sql .= " WHERE rowid = ?";

        $requete = $this->getBdd()->prepare($sql);
        return $requete->execute([$description, $this->rowid]);
    }

    public function updateOpen($open)
    {
        $sql = "UPDATE project";
        $sql .= " SET open = ?";
        $sql .= " WHERE rowid = ?";

        $requete = $this->getBdd()->prepare($sql);
        return $requete->execute([$open, $this->rowid]);
    }

    public function updateActive($active)
    {
        $sql = "UPDATE project";
        $sql .= " SET active = ?";
        $sql .= " WHERE rowid = ?";

        $requete = $this->getBdd()->prepare($sql);
        return $requete->execute([$active, $this->rowid]);
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

    public function fetch_members_count($projectId = null)
    {
        $projectId = $this->id ?? $projectId;

        $sql = "SELECT COUNT(u.rowid) as membersCount";
        $sql .= " FROM project as p";
        $sql .= " LEFT JOIN work_to as w ON p.rowid = w.fk_project";
        $sql .= " LEFT JOIN belong_to as b ON b.fk_team = w.fk_team";
        $sql .= " LEFT JOIN users as u ON b.fk_user = u.rowid";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$projectId]);

        $membersCount = $requete->fetch(PDO::FETCH_OBJ);

        if($membersCount == false)
        {
            $membersCount = new stdClass;
            $membersCount->membersCount = 0;
        }

        return $membersCount->membersCount;
    }


    // INSERT
    
    /** Create object in db
     * 
     */
    public function create($name, $type, $description = "", int $fk_organization)
    {
        $sql = "INSERT INTO project (name, type, open, description, fk_organization, active)";
        $sql .= " VALUES (?,?,NOW(),?,?,1)";

        $requete = $this->getBdd()->prepare($sql);

        return $requete->execute([$name, $type, $description, $fk_organization]);
    }
}