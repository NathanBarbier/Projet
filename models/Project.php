<?php

Class Project extends Modele
{
    protected ?int      $rowid              = null;
    protected ?string   $name               = null;
    protected ?string   $type               = null;
    protected ?string   $open               = null;
    protected ?string   $description        = null;
    protected ?int      $fk_organization    = null;
    protected ?array    $teams              = array();
    protected ?int      $active             = null;

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

        $sql .= " name = :name";
        $sql .= " ,type = :type";
        $sql .= " ,description = :description";
        $sql .= " ,fk_organization = :fk_organization";

        if($this->open) {
            $sql .= " ,open = :open";
        }
        if($this->active !== null) {
            $sql .= " ,active = :active";
        }

        $sql .= " WHERE rowid = :rowid;";

        // prepare
        $requete = $this->getBdd()->prepare($sql);

        // Bind optional parameter
        if($this->open) {
            $requete->bindParam(':open', $this->open, PDO::PARAM_STR);
        }
        if($this->active !== null) {
            $requete->bindParam(':active', $this->active, PDO::PARAM_INT);
        }

        // Bind required parameters
        $requete->bindParam(':name', $this->name, PDO::PARAM_STR);
        $requete->bindParam(':type', $this->type, PDO::PARAM_STR);
        $requete->bindParam(':description', $this->description, PDO::PARAM_STR);
        $requete->bindParam(':fk_organization', $this->fk_organization, PDO::PARAM_INT);
        $requete->bindParam(':rowid', $this->rowid, PDO::PARAM_INT);

        return $requete->execute();
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
        }
    }

    public function fetchName()
    {
        $sql = "SELECT name FROM storieshelper_project WHERE rowid = ?";
        
        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$this->rowid]);

        if($requete->rowCount() > 0)
        {
            $obj = $requete->fetch(PDO::FETCH_OBJ);
            $this->name = $obj->name;
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

    /**
     * Depth : 0 = basic team properties | 1 = add team users | 2 = add columns
     */
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

        return intval($obj->rowid);
    }

    // delete

    public function delete()
    {
        // delete project
        $sql = "DELETE FROM storieshelper_project WHERE rowid = ?;";
        
        $requete = $this->getBddSafe()->prepare($sql);
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