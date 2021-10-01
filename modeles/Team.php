<?php
class Team extends Modele
{
    private $id;
    private $name;
    private $idOrganization;
    private $idProject;
    private $members = [];

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

            $sql = "SELECT u.rowid AS userId, u.lastname, u.firstname, u.birth, u.password, u.fk_position, u.email, u.fk_organization";
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

    public function countMembers()
    {
        return count($this->members);
    }

    //! FETCH

    public function fetchAll($idorganization)
    {
        $idorganization = $this->id ?? $idorganization;

        $sql = "SELECT *"; 
        $sql .= " FROM teams"; 
        $sql .= " WHERE idorganization = ?";
        
        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$idorganization]);

        return $requete->fetchAll(PDO::FETCH_OBJ);
    }

    public function fetch($idTeam)
    {
        $sql = "SELECT *"; 
        $sql .= " FROM teams"; 
        $sql .= " WHERE rowid = ?";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$idTeam]);

        return $requete->fetch(PDO::FETCH_OBJ);
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

        $sql = "SELECT t.rowid, t.name, t.fk_organization, t.fk_project";
        $sql .= " FROM teams AS t";
        $sql .= " WHERE rowid IN ('$teamIds')";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute();

        return $requete->fetchAll(PDO::FETCH_OBJ);
    }

    public function fetchByProjectId($fk_project)
    {
        $sql = "SELECT t.rowid, t.name, t.fk_organization, t.fk_project";
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
        $sql = "INSERT INTO teams (name, fk_organization, fk_project)";
        $sql .= " VALUES (?,?,?)";

        $requete = $this->getBdd()->prepare($sql);
        return $requete->execute([$name, $fk_organization, $fk_project]);
    } 


    //! UPDATE

    public function updateName($name, $teamId = null)
    {
        if($teamId == null)
        {
            $teamId = $this->id;
        }

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
}