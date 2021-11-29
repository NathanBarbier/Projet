<?php

Class Project extends Modele
{
    private $id;
    private $name;
    private $type;
    private $open;
    private $description;
    private $idOrganization;
    private $teams = array();
    private $active;

    public function __construct($idProject = null)
    {
        if($idProject !== null)
        {
            $sql = "SELECT p.rowid, p.name, p.type, p.open, p.fk_organization, p.description, p.active";
            $sql .= " FROM projects AS p";
            $sql .= " WHERE p.rowid = ?";

            $requete = $this->getBdd()->prepare($sql);
            $requete->execute([$idProject]);

            $Project = $requete->fetch(PDO::FETCH_OBJ);

            if($Project != false)
            {
                $this->id = $idProject;
                $this->name = $Project->name;
                $this->type = $Project->type;
                $this->open = $Project->open;
                $this->description = $Project->description;
                $this->idOrganization = $Project->fk_organization;
                $this->active = $Project->active;
            }

            $Team = new Team();
            $lines = $Team->fetchAll($this->id);

            foreach($lines as $line)
            {
                $Team = new Team($line->rowid);
                $this->teams[] = $Team;
            }
                
        }
    }

    //! GETTER
    public function getId()
    {
        return $this->id;
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

    public function getIdorganization()
    {
        return $this->idorganization;
    }

    public function getDescritpion()
    {
        return $this->description;
    }

    public function getTeams()
    {
        return $this->teams;
    }

    public function getActive()
    {
        return $this->active;
    }


    //! SETTER

    public function setId(int $id)
    {
        $this->id = $id;
    }

    public function setName(string $name)
    {
        $this->name = $name;
    }

    public function setType(string $type)
    {
        $this->type = $type;
    }

    public function setIdOrganization($idOrganization)
    {
        $this->idOrganization = $idOrganization;
    }

    public function setActive($active)
    {
        $this->active = $active;
    }


    //! UPDATE

    public function updateName($name, $idProject = null)
    {
        if($idProject == null)
        {
            $idProject = $this->id;
        }

        $sql = "UPDATE projects";
        $sql .= " SET name = ?"; 
        $sql .= " WHERE rowid =  ?";

        $requete = $this->getBdd()->prepare($sql);
        return $requete->execute([$name, $idProject]);
    }

    public function updateType(string $type, $idProject = null)
    {
        if($idProject == null)
        {
            $idProject = $this->id;
        }

        $sql = "UPDATE projects";
        $sql .= " SET type = ?";
        $sql .= " WHERE rowid =  ?";

        $requete = $this->getBdd()->prepare($sql);
        return $requete->execute([$type, $idProject]);
    }

    public function updateDescription(string $description, $idProject = null)
    {
        if($idProject == null)
        {
            $idProject = $this->id;
        }

        $sql = "UPDATE projects";
        $sql .= " SET description = ?";
        $sql .= " WHERE rowid = ?";

        $requete = $this->getBdd()->prepare($sql);
        return $requete->execute([$description, $idProject]);
    }

    public function updateOpen($open, $idProject = null)
    {
        $idProject = $idProject == null ? $this->id : $idProject;

        $sql = "UPDATE projects";
        $sql .= " SET open = ?";
        $sql .= " WHERE rowid = ?";

        $requete = $this->getBdd()->prepare($sql);
        return $requete->execute([$open, $idProject]);
    }

    public function updateActive($active, $idProject = null)
    {
        $idProject = $idProject == null ? $this->id : $idProject;

        $sql = "UPDATE projects";
        $sql .= " SET active = ?";
        $sql .= " WHERE rowid = ?";

        $requete = $this->getBdd()->prepare($sql);
        return $requete->execute([$active, $idProject]);
    }


    //! FETCH

    public function fetchAll($idOrganization = null)
    {
        $idOrganization = $idOrganization == null ? $this->idOrganization : $idOrganization;

        $sql = "SELECT p.rowid, p.name, p.type, p.open, p.description, p.fk_organization, p.active";
        $sql .= " FROM projects AS p";
        $sql .= " WHERE p.fk_organization = ?";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$idOrganization]);

        $lines = $requete->fetchAll(PDO::FETCH_OBJ);

        return $lines;
    }

    public function fetchByTeam($idTeam)
    {
        $sql = "SELECT e.name AS teamName, p.name AS projectName, p.type";
        $sql .= " FROM work_to AS w";
        $sql .= " INNER JOIN teams AS t ON w.fk_team = t.rowid"; 
        $sql .= " INNER JOIN projects AS p t.rowid = p.fk_team";
        $sql .= " WHERE w.fk_team = ?";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$idTeam]);

        return $requete->fetchAll(PDO::FETCH_OBJ);
    } 

    public function fetchMaxId()
    {
        $sql = "SELECT max(rowid) AS maxId";
        $sql .=" FROM projects";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute();

        return $requete->fetch(PDO::FETCH_OBJ);
    }

    public function fetch_members_count($projectId = null)
    {
        $projectId = $this->id ?? $projectId;

        $sql = "SELECT COUNT(u.rowid) as membersCount";
        $sql .= " FROM projects as p";
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


    //! INSERT
    public function create($name, $type, $description = "", $idorganization = false)
    {
        $idorganization = $idorganization ?? $this->getIdorganization();
        $status = array();

        $sql = "INSERT INTO projects (name, type, open, description, fk_organization, active)";
        $sql .= " VALUES (?,?,NOW(),?,?, 1)";

        $requete = $this->getBdd()->prepare($sql);

        $status[] = $requete->execute([$name, $type, $description, $idorganization]);

        if(in_array(false, $status))
        {
            return false;
        }
        else
        {
            return true;
        }
    }
}