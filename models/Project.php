<?php

Class Project extends Modele
{
    public $rowid;
    protected $name;
    protected $type;
    protected $open;
    protected $description;
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
        $sql = "UPDATE storieshelper_project";
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
        $sql .= " FROM storieshelper_project AS p";
        $sql .= " WHERE p.rowid = ?";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$rowid]);

        if($requete->rowCount() > 0)
        {
            $obj = $requete->fetch(PDO::FETCH_OBJ);

            $this->rowid            = $rowid;
            $this->name             = $obj->name;
            $this->type             = $obj->type;
            $this->open             = $obj->open;
            $this->description      = $obj->description;
            $this->active           = $obj->active;
            $this->fk_organization  = $obj->fk_organization;
            
            $this->fetchTeams();
            // $this->fetchOrganization();
        }
    }

    /**
     * @param object $Obj the mysql 'Project' object
     * @param int $depth
     */
    public function initialize($Obj,int $depth)
    {
        $this->rowid            = intval($Obj->rowid);
        $this->name             = $Obj->name;
        $this->type             = $Obj->type;
        $this->open             = $Obj->open;
        $this->description      = $Obj->description;
        $this->active           = $Obj->active;
        $this->fk_organization  = intval($Obj->fk_organization);
        
        if($depth > 0) 
        {
            $this->fetchTeams($depth);
        }
    }

    public function fetchTeams(int $depth = 2)
    {
        $sql = "SELECT *"; 
        $sql .= " FROM storieshelper_team"; 
        $sql .= " WHERE fk_project = ?";
        $sql .= " ORDER BY name ASC";
        
        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$this->rowid]);

        if($requete->rowCount() > 0)
        {
            $lines = $requete->fetchAll(PDO::FETCH_OBJ);
            
            foreach($lines as $line)
            {
                $Team = new Team();
                $Team->initialize($line, $depth);
                $this->teams[] = $Team;
            }
        }
    }


    // INSERT
    
    /** Create object in db
     * 
     */
    public function create()
    {
        $sql = "INSERT INTO storieshelper_project (name, type, open, description, fk_organization, active)";
        $sql .= " VALUES (?,?,NOW(),?,?,1)";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$this->name, $this->type, $this->description, $this->fk_organization]);

        $sql = "SELECT MAX(rowid) AS rowid FROM storieshelper_project";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute();
        $obj = $requete->fetch(PDO::FETCH_OBJ);

        return $obj->rowid;
    }

    // delete

    public function delete()
    {
        // delete project
        $sql = "DELETE FROM storieshelper_project WHERE rowid = ?;";
        
        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$this->rowid]);
    }

    // methods

    /**
     * Check if the team belongs to this Project
     * @param int fk_team the team to check
     * @return bool true OK, false KO
     */
    public function checkTeam(int $fk_team)
    {
        foreach($this->teams as $Team)
        {
            if($Team->getRowid() == $fk_team)
            {
                return true;
            }
        }
        return false;
    }
}