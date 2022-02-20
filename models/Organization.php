<?php
class Organization extends Modele
{
    protected $rowid;
    protected $name;
    protected $users = [];
    protected $projects = [];
    protected $logs = [];
    protected $privacy;

    /**
     * @param int rowid The Organization id
     * @param int privacy The level of privacy of the recovered informations | 0 = fetch all | 1 = fetch user emails | 2 = max privacy level
     */
    public function __construct(int $rowid = 0, int $privacy = 2)
    {
        if($rowid != 0)
        {
            $this->rowid    = $rowid;
            $this->privacy   = $privacy;
            $this->fetch();
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

    public function getLogs()
    {
        return $this->logs;
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
        $sql = "INSERT INTO storieshelper_organization (name)";
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
        // delete task comments
        $sql = "DELETE FROM storieshelper_task_comment tc INNER JOIN storieshelper_task tk ON tk.rowid = tc.fk_task INNER JOIN storieshelper_map_column mc ON mc.rowid = tk.fk_column INNER JOIN storieshelper_team tm ON tm.rowid = mc.fk_team INNER JOIN storieshelper_project p ON p.rowid = tm.fk_project INNER JOIN storieshelper_organization o ON o.rowid = p.fk_organization WHERE o.rowid = ?;";
        
        // delete task member
        $sql .= "DELETE FROM storieshelper_task_member tm INNER JOIN storieshelper_task tk ON tk.rowid = tm.fk_task INNER JOIN storieshelper_map_column mc ON mc.rowid = tk.fk_column INNER JOIN storieshelper_team tm ON tm.rowid = mc.fk_team INNER JOIN storieshelper_project p ON p.rowid = tm.fk_project INNER JOIN storieshelper_organization o ON o.rowid = p.fk_organization WHERE o.rowid = ?;";

        // delete tasks
        $sql .= "DELETE FROM storieshelper_task tk ON INNER JOIN storieshelper_map_column mc ON mc.rowid = tk.fk_column INNER JOIN storieshelper_team tm ON tm.rowid = mc.fk_team INNER JOIN storieshelper_project p ON p.rowid = tm.fk_project INNER JOIN storieshelper_organization o ON o.rowid = p.fk_organization WHERE o.rowid = ?;";

        // delete all map columns
        $sql .= "DELETE FROM storieshelper_map_column mc INNER JOIN storieshelper_team tm ON tm.rowid = mc.fk_team INNER JOIN storieshelper_project p ON p.rowid = tm.fk_project INNER JOIN storieshelper_organization o ON o.rowid = p.fk_organization WHERE o.rowid = ?;";

        // delete all belongs_to
        $sql .= "DELETE FROM storieshelper_belong_to bt INNER JOIN storieshelper_team tm ON tm.rowid = bt.fk_team INNER JOIN storieshelper_project p ON p.rowid = tm.fk_project INNER JOIN storieshelper_organization o ON o.rowid = p.fk_organization WHERE o.rowid = ?;";

        // delete all teams
        $sql .= "DELETE FROM storieshelper_team tm INNER JOIN storieshelper_project p ON p.rowid = tm.fk_project INNER JOIN storieshelper_organization o ON o.rowid = p.fk_organization WHERE o.rowid = ?;";

        // delete projects
        $sql .= "DELETE FROM storieshelper_project p INNER JOIN storieshelper_organization o ON o.rowid = p.fk_organization WHERE o.rowid = ?;";

        // delete all users
        $sql .= "DELETE FROM storieshelper_user WHERE fk_organization = ?;";
        
        // delete organization
        $sql .= "DELETE FROM storieshelper_organization WHERE rowid = ?;";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$this->rowid,$this->rowid,$this->rowid,$this->rowid,$this->rowid,$this->rowid,$this->rowid,$this->rowid,$this->rowid]);

        session_destroy();
    }


    // FETCH

    public function fetch()
    {
        $sql = "SELECT *";
        $sql .= " FROM storieshelper_organization";
        $sql .= " WHERE rowid = ?";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$this->rowid]);

        if($requete->rowCount() > 0)
        {
            $obj = $requete->fetch(PDO::FETCH_OBJ);
            
            $this->rowid    = intval($obj->rowid);
            $this->name     = $obj->name;

            $this->fetchUsers();
            $this->fetchProjects();

            if($this->privacy == 0) {
                $this->fetchLogs();
            }
            
            return true;
        }
        else
        {
            return false;
        }
    }

    public function fetchUsers()
    {
        $sql = "SELECT *";
        $sql .= " FROM storieshelper_user";
        $sql .= " WHERE fk_organization = ?";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$this->rowid]);
        
        if($requete->rowCount() > 0)
        {
            $lines = $requete->fetchAll(PDO::FETCH_OBJ);

            // position the current user at the top of the list
            $idUser = $_SESSION['idUser'];
            foreach($lines as $line)
            {
                if($line->rowid == $idUser) {
                    $User = new User();
                    $User->initialize($line, $this->privacy);
                    $this->users[] = $User;
                    break;
                }
            }

            foreach($lines as $line)
            {
                if($line->rowid != $idUser) {
                    $User = new User();
                    $User->initialize($line, $this->privacy);
                    $this->users[] = $User;
                }
            }
        }
    }

    public function fetchProjects()
    {
        $sql = "SELECT *";
        $sql .= " FROM storieshelper_project";
        $sql .= " WHERE fk_organization = ?";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$this->rowid]);

        if($requete->rowCount() > 0)
        {
            $lines = $requete->fetchAll(PDO::FETCH_OBJ);
        
            foreach($lines as $line)
            {
                $Project = new Project();
                $Project->initialize($line);
                $this->projects[] = $Project;
            }
        }
    }

    public function fetchLogs()
    {
        $sql = "SELECT *";
        $sql .= " FROM storieshelper_log_history";
        $sql .= " WHERE fk_organization = ?";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$this->rowid]);

        if($requete->rowCount() > 0)
        {
            $lines = $requete->fetchAll(PDO::FETCH_OBJ);

            foreach($lines as $line)
            {
                $LogHistory = new LogHistory();
                $LogHistory->initialize($line);
                $this->logs[] = $LogHistory;
            }
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
        return false;
    }

    public function fetch_last_insert_id()
    {
        $sql = "SELECT MAX(rowid) as rowid";
        $sql .= " FROM storieshelper_organization";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute();

        return $requete->fetch(PDO::FETCH_OBJ)->rowid;
    }


    // METHODES

    public function checkByName($name)
    {
        $sql = "SELECT *";
        $sql .= " FROM storieshelper_organization";
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

    /**
     * Check if the project belongs to this Organization
     * @param int fk_project the project to check
     * @return bool true OK, false KO
     */
    public function checkProject(int $fk_project)
    {
        foreach($this->projects as $Project)
        {
            if($Project->getRowid() == $fk_project)
            {
                return true;
            }
        }
        return false;
    }
}
?>