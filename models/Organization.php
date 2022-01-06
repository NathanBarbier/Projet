<?php
class Organization extends Modele
{
    private $id;
    private $name;
    private $email;
    private $password;
    private $users = [];
    private $projects = [];

    public function __construct($id = null)
    {
        if($id != null)
        {
            $sql = "SELECT * ";
            $sql .= " FROM organizations"; 
            $sql .= " WHERE rowid = ?";

            $requete = $this->getBdd()->prepare($sql);
            $requete->execute([$id]);

            $Organization = $requete->fetch(PDO::FETCH_OBJ);
            
            $this->id = $id;
            $this->name = $Organization->name;
            $this->email = $Organization->email;
            $this->password = $Organization->password;

            $User = new User();
            $users = $User->fetchAll($this->id);

            foreach($users as $user)
            {
                $obj = new User($user->rowid);
                $this->users[] = $obj;
            }

            $Project = new Project();
            $projects = $Project->fetchAll($this->id);
            foreach($projects as $project)
            {
                $obj = new Project($project->rowid);
                $this->projects[] = $obj; 
            }
        }
    }


    //! SETTER

    public function setName($name)
    {
        $this->name = $name;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function setPassword($password)
    {
        $this->password = password_hash($password, PASSWORD_BCRYPT);
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

    public function getEmail()
    {
        return $this->email;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getUsers()
    {
        return $this->users;
    }

    public function getProjects()
    {
        return $this->projects;
    }

    //* outdated, use instead $this->getUsers()
    public function getOrgUsersInfos()
    {
        $usersInfos = array();
        foreach($this->getUsers() as $user)
        {
            $userInfos  = new stdClass;

            $userInfos->id = $user->getId();
            $userInfos->idTeam = $user->getTeamId();
            $userInfos->idOrganization = $user->getOrganizationId();
            $userInfos->firstname = $user->getFirstname();
            $userInfos->lastname = $user->getLastname();
            $userInfos->email = $user->getEmail();
            $userInfos->birth = $user->getBirth();
            $userInfos->password = $user->getPassword();

            $usersInfos[] = $userInfos;
        }
        return $usersInfos;
    }

    public function getMaxIdUser()
    {
        foreach($this->getUsers() as $cle => $user)
        {
            $idUser = $user->getIdUser();
            $maxIdUser = null;
            if ($cle == 0)
            {
                $maxIdUser = $user->getIdUser();
            }
            else
            {
                if($maxIdUser < $idUser)
                {
                    $maxIdUser = $idUser;
                }
            }
        }

        return $maxIdUser;
    }

    public function getMaxIdProject()
    {
        foreach($this->getProjects() as $key => $project)
        {
            $projectId = $project->getIdProjet();
            $projectMaxId = null;
            if($key == 0)
            {
                $projectMaxId = $project->getIdProjet();
            } 
            else 
            {
                if($projectMaxId < $projectId)
                {
                    $projectMaxId = $projectId;
                }
            }
        }
        return $projectMaxId;
    }

    //* outdated, use directly count($object->users)
    public function countUsers()
    {
        return count($this->users);
    }

    //* outdated, use directly count($object->projects)
    public function countProjects()
    {
        return count($this->projects);
    }

    public function countActiveProjects()
    {
        $counter = 0;
        foreach($this->projects as $project)
        {
            if($project->getActive() == 0)
            {
                $counter++;
            }
        }

        return $counter;
    }


    // UPDATE

    public function updateName($name)
    {
        $sql = "UPDATE organizations"; 
        $sql .= " SET nom = ?";
        $sql .= " WHERE idorganization = ?";

        $requete = $this->getBdd()->prepare($sql);
        return $requete->execute([$name, $this->id]);
    }

    public function updateEmail($email, $rowid = null)
    {
        $rowid = $rowid == null ? $this->id : $rowid;

        /*$sql = "UPDATE organizations"; 
        $sql .= " SET email = ?";
        $sql .= " WHERE rowid = ?";*/
        $sql = "SET @rowid = ?;";
        $sql .= " SET @email = ?;";
        $sql .= " CALL PROCEDURE updateEmail(@rowid, @email, @status);";
        $sql .= " SELECT @status;";

        $requete = $this->getBdd()->prepare($sql);
        return $requete->execute([$email, $this->id]);
    }

    public function updatePassword($password, $organizationId = null)
    {
        $organizationId = $organizationId == null ? $this->id : $organizationId;

        $sql = "UPDATE organizations"; 
        $sql .= " SET password = ?";
        $sql .= " WHERE rowid = ?";

        $requete = $this->getBdd()->prepare($sql);
        return $requete->execute([$password, $organizationId]);
    }


    // DELETE 

    public function delete()
    {
        $status = array();

        $sql = "DELETE FROM work_to AS w";
        $sql .= " INNER JOIN teams AS t ON w.fk_team = t.rowid";
        $sql .= " WHERE fk_organization = ?";
        
        $requete = $this->getBdd()->prepare($sql);
        $status [] = $requete->execute([$this->id]);
    
        $sql = "DELETE FROM projects"; 
        $sql .= " WHERE fk_organization = ?";

        $requete = $this->getBdd()->prepare($sql);
        $status [] = $requete->execute([$this->id]);
    
        $sql = "DELETE FROM users"; 
        $sql .= " WHERE fk_organization = ?";

        $requete = $this->getBdd()->prepare($sql);
        $status [] = $requete->execute([$this->id]);
        
        $sql = "DELETE FROM teams";
        $sql .= " WHERE fk_organization = ?";

        $requete = $this->getBdd()->prepare($sql);
        $status [] = $requete->execute([$this->id]);
        
        $sql = "DELETE FROM organizations";
        $sql .= " WHERE rowid = ?";
        
        $requete = $this->getBdd()->prepare($sql);
        $status [] = $requete->execute([$this->id]);
    
        session_destroy();

        if(in_array(false, $status))
        {
            return false;
        }
        else 
        {
            return true;
        }
    }


    // FETCH

    public function fetch($rowid = null)
    {
        $rowid = $rowid == null ? $this->id : $rowid;

        $sql = "SELECT *";
        $sql .= " FROM organizations";
        $sql .= " WHERE rowid = ?";

        $requete = $this->getBdd()->prepare($sql);
        $status = $requete->execute([$rowid]);
    
        if($status)
        {
            $Organization = $requete->fetch(PDO::FETCH_OBJ);
            
            $this->id = $rowid;
            $this->name = $Organization->name;
            $this->email = $Organization->email;
            $this->password = $Organization->password;

            $User = new User();
            $users = $User->fetchAll($this->id);

            foreach($users as $user)
            {
                $obj = new User($user->rowid);
                $this->users[] = $obj;
            }

            $Project = new Project();
            $projects = $Project->fetchAll($this->id);
            foreach($projects as $project)
            {
                $obj = new Project($project->rowid);
                $this->projects[] = $obj; 
            }
            return true;
        }
        else
        {
            return false;
        }
    }

    public function fetchByEmail($email)
    {
        $sql = "SELECT *";
        $sql .= " FROM organizations"; 
        $sql .= " WHERE email = ?";
        
        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$email]);

        return $requete->fetch(PDO::FETCH_OBJ);
    }


    //! METHODES

    public function checkByName($name)
    {
        $sql = "SELECT *";
        $sql .= " FROM organizations";
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
            
    public function checkToken($idUser, $token)
    {
        $sql = "SELECT *";
        $sql .= " FROM organizations";
        $sql .= " WHERE rowid = ?";
        $sql .= " AND token = ?";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$idUser, $token]);

        if($requete->rowcount() > 0)
        {
            return true;
        } 
        else 
        {
            return false;
        }
    }

    public function checkByEmail($email)
    {
        $sql = "SELECT * ";
        $sql .= " FROM organizations";
        $sql .= " WHERE email = ?";
        
        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$email]);

        if($requete->rowCount() > 0)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
        
    public function addCookie($idUser, $token)
    {
        $sql = "UPDATE organizations";
        $sql .= " SET token = ?";
        $sql .= " WHERE rowid = ?";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$token, $idUser]);
    }
}
?>