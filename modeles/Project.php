<?php

Class Project extends Modele
{
    private $id;
    private $name;
    private $type;
    private $open;
    private $deadline;
    private $description;
    private $idOrganization;
    private $tasks = array();

    public function __construct($idProject = null)
    {
        if($idProject !== null)
        {
            $sql = "SELECT p.rowid, p.name, p.type, p.open, p.deadline, p.fk_organization, p.description";
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
                $this->deadline = $Project->deadline;
                $this->open = $Project->open;
                $this->description = $Project->description;
                $this->idOrganization = $Project->fk_organization;
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

    public function getDeadline()
    {
        return $this->deadline;
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

    public function setDeadline($deadline)
    {
        $this->deadline = $deadline;
    }

    public function setIdorganization($idorganization)
    {
        $this->idorganization = $idorganization;
    }


    //! UPDATE

    public function updateId($id)
    {
        $sql = "UPDATE projects";
        $sql .= " SET rowid = ?"; 
        $sql .= " WHERE rowid =  ?";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$id, $this->id]);
    }

    public function updateNom($nom)
    {
        $sql = "UPDATE projects";
        $sql .= " SET name = ?"; 
        $sql .= " WHERE rowid =  ?";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$nom, $this->id]);
    }

    public function updateType(string $type)
    {
        $sql = "UPDATE projects";
        $sql .= " SET type = ?";
        $sql .= " WHERE rowid =  ?";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$type, $this->id]);
    }

    public function updateDeadline($deadline)
    {
        $sql = "UPDATE projects";
        $sql .= " SET deadline = ?"; 
        $sql .= " WHERE rowid =  ?";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$deadline, $this->id]);
    }

    public function updateOpen($open)
    {
        $sql = "UPDATE projects";
        $sql .= " SET open = ?";
        $sql .= " WHERE rowid = ?";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$open, $this->id]);
    }


    //! FETCH

    public function fetchAll($idorganization = null)
    {
        if($idorganization == null)
        {
            $idorganization = $this->idorganization;
        }

        $sql = "SELECT p.rowid, p.name, p.type, p.open, p.deadline, p.description, p.fk_organization";
        $sql .= " FROM projects AS p";
        $sql .= " WHERE p.fk_organization = ?";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$idorganization]);

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

        return $requete->fetch(PDO::FETCH_OBJ);
    }


    //! INSERT
    public function create($name, $type, $deadline = NULL, $description = "", $idorganization = false)
    {
        $idorganization = $idorganization ?? $this->getIdorganization();
        $status = array();

        $sql = "INSERT INTO map_columns (name, fk_project)";
        $sql .= " VALUES ('Open', ?),('Ready', ?),('In progress', ?),('Closed', ?)";

        $requete = $this->getBdd()->prepare($sql);
        $status[] = $requete->execute([$this->id, $this->id, $this->id, $this->id]);

        $sql = "INSERT INTO projects (name, type, open, deadline, description, fk_organization)";
        $sql .= " VALUES (?,?,NOW(),?,?,?)";

        $requete = $this->getBdd()->prepare($sql);

        $status[] = $requete->execute([$name, $type, $deadline, $description, $idorganization]);

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