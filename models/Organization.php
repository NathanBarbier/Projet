<?php
class Organization extends Modele
{
    protected $rowid;
    protected $name;
    protected $users = [];
    protected $projects = [];

    public function __construct($rowid = null)
    {
        if($rowid != null)
        {
            $this->fetch($rowid);
        }
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


    // GETTER
    public function getRowid()
    {
        return $this->rowid;
    }

    public function getName()
    {
        return $this->name;
    }


    public function getUsers()
    {
        return $this->users;
    }

    public function getProjects()
    {
        return $this->projects;
    }

    public function getMaxIdUser()
    {
        return max(array_map(function($user) {
            return $user->rowid;
        }, $this->users));
    }

    public function getMaxIdProject()
    {
        return max(array_map(function($project) {
            return $project->rowid;
        }, $this->projects));
    }

    public function countActiveProjects()
    {
        $counter = 0;
        foreach(array_column($this->projects, 'active') as $value)
        {
            if($value == 1)
            {
                $counter++;
            }
        }
        return $counter;
    }

    public function countArchivedProjects()
    {
        $counter = 0;
        foreach(array_column($this->projects, 'active') as $value)
        {
            if($value == 0)
            {
                $counter++;
            }
        }
        return $counter;
    }

    public function removeUser(int $fk_user)
    {
        foreach($this->users as $key => $User)
        {
            if($User->getRowid() == $fk_user)
            {
                unset($this->users[$key]);
            }
        }
    }

    // CREATE

    public function create()
    {
        $sql = "INSERT INTO organization (name)";
        $sql .= " VALUES (?)";

        $requete = $this->getBdd()->prepare($sql);
        return $requete->execute([$this->name]);
    }


    // UPDATE

    public function update()
    {
        $sql = "UPDATE organization"; 
        $sql .= " SET name = ?";
        $sql .= " WHERE rowid = ?";

        $requete = $this->getBdd()->prepare($sql);
        return $requete->execute([$this->name, $this->rowid]);
    }

    // DELETE 

    public function delete()
    {
        $sql = "DELETE FROM belong_to AS w";
        $sql .= " INNER JOIN team AS t ON w.fk_team = t.rowid";
        $sql .= " WHERE fk_organization = ?";
        
        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$this->rowid]);
    
        $sql = "DELETE FROM project"; 
        $sql .= " WHERE fk_organization = ?";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$this->rowid]);
    
        $sql = "DELETE FROM user"; 
        $sql .= " WHERE fk_organization = ?";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$this->rowid]);
        
        $sql = "DELETE FROM team";
        $sql .= " WHERE fk_organization = ?";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$this->rowid]);
        
        $sql = "DELETE FROM organization";
        $sql .= " WHERE rowid = ?";
        
        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$this->rowid]);
    
        session_destroy();
    }


    // FETCH

    public function fetch($rowid = null)
    {
        $rowid = $rowid == null ? $this->rowid : $rowid;

        $sql = "SELECT *";
        $sql .= " FROM organization";
        $sql .= " WHERE rowid = ?";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$rowid]);

    
        if($requete->rowCount() > 0)
        {
            $obj = $requete->fetch(PDO::FETCH_OBJ);
            
            $this->rowid = $rowid;
            $this->name = $obj->name;

            $this->fetchUsers();
            $this->fetchProjects();
            
            return true;
        }
        else
        {
            return false;
        }
    }

    public function fetchUsers()
    {
        $sql = "SELECT rowid";
        $sql .= " FROM user";
        $sql .= " WHERE fk_organization = ?";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$this->rowid]);
        
        $lines = $requete->fetchAll(PDO::FETCH_OBJ);

        foreach($lines as $line)
        {
            $this->users[] = new User($line->rowid);
        }
    }

    public function fetchProjects()
    {
        $sql = "SELECT p.rowid";
        $sql .= " FROM project AS p";
        $sql .= " WHERE p.fk_organization = ?";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$this->rowid]);

        $lines = $requete->fetchAll(PDO::FETCH_OBJ);
    
        foreach($lines as $line)
        {
            $this->projects[] = new Project($line->rowid);
        }
    }

    /**
     * Return the matching user in the Organization users property
     * @param int $idUser 
     * @return User $User the user matching id user
     */
    public function fetchUser(int $idUser)
    {
        foreach($this->users as $User)
        {
            if($User->getRowid() == $idUser)
            {
                return $User;
            }
        }
    }

    public function fetch_last_insert_id()
    {
        $sql = "SELECT MAX(rowid) as rowid";
        $sql .= " FROM organization";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute();

        return $requete->fetch(PDO::FETCH_OBJ)->rowid;
    }


    // METHODES

    public function checkByName($name)
    {
        $sql = "SELECT *";
        $sql .= " FROM organization";
        $sql .= " WHERE name = ?";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$name]);

        if($requete->rowCount() > 0)
        {
            return true;
        }
        else
        {
            return false;
        }

    }
}
?>