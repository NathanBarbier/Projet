<?php
class Team extends Modele
{
    protected int $rowid;
    protected array $name;
    protected int $fk_project;
    protected array $users;
    protected array $mapColumns;
    protected int $active;

    public function __construct($rowid = null)
    {
        if($rowid != null)
        {
            $this->fetch($rowid);
        }
    }


    // SETTER
    
    public function setRowid($rowid)
    {
        $this->rowid = $rowid;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function setFk_project(int $fk_project)
    {
        $this->fk_project = $fk_project;
    }

    public function setUsers(array $users)
    {
        $this->users = $users;
    }

    public function setMapColumns(array $mapColumns)
    {
        $this->mapColumns = $mapColumns;
    }

    public function setActive(int $active)
    {
        $this->active = $active;
    }

    public function addUser(User $User)
    {
        $this->users[] = $User;
    }

    public function removeUser(int $fk_user)
    {
        $key = array_search($fk_user, array_column($this->object_to_array($this->users), 'rowid'));
        unset($this->users[$key]);
    }

    // GETTER

    public function getName()
    {
        return $this->name;
    }

    public function getRowid()
    {
        return $this->rowid;
    }

    public function getFk_project()
    {
        return $this->fk_project;
    }

    public function getOrganization()
    {
        return $this->Organization;
    }

    public function getUsers()
    {
        return $this->users;
    }

    public function getMapColumns()
    {
        return $this->mapColumns;
    }

    public function isActive()
    {
        return intval($this->active);
    }

    // FETCH

    public function fetch(int $rowid)
    {
        $sql = "SELECT *"; 
        $sql .= " FROM storieshelper_team";
        $sql .= " WHERE rowid = ?";
        
        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$rowid]);

        if($requete->rowCount() > 0)
        {
            $obj = $requete->fetch(PDO::FETCH_OBJ);
            $this->rowid = $rowid;
            $this->name = $obj->name;
            // $this->Project = new Project($obj->fk_project);
            $this->fk_project = $obj->fk_project;
            $this->active = $obj->active;
        }       
        // fetch team users
        $sql = "SELECT *";
        $sql .= " FROM storieshelper_user AS u";
        $sql .= " LEFT JOIN storieshelper_belong_to AS b ON u.rowid = b.fk_user";
        $sql .= " WHERE b.fk_team = ?";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$this->rowid]);

        if($requete->rowCount() > 0)
        {
            $lines = $requete->fetchAll(PDO::FETCH_OBJ);

            foreach($lines as $line)
            {
                $User = new User();
                $User->initialize($line, true);
                $this->users[] = $User;
            }
        }

        // fetch team / board columns
        $sql = "SELECT *";
        $sql .= " FROM storieshelper_map_column AS m";
        $sql .= " WHERE m.fk_team = ?";
        $sql .= " ORDER BY m.rank ASC";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$this->rowid]);

        if($requete->rowCount() > 0)
        {
            $lines = $requete->fetchAll(PDO::FETCH_OBJ);

            foreach($lines as $line)
            {
                $MapColumn = new MapColumn();
                $MapColumn->initialize($line);
                $this->mapColumns[] = $MapColumn;  
            }
        }
    }

    public function initialize(object $Obj, int $depth)
    {
        $this->rowid        = $Obj->rowid;
        $this->name         = $Obj->name;
        $this->fk_project   = $Obj->fk_project;
        $this->active       = $Obj->active;

        // fetch team users
        $sql = "SELECT *";
        $sql .= " FROM storieshelper_user AS u";
        $sql .= " LEFT JOIN storieshelper_belong_to AS b ON u.rowid = b.fk_user";
        $sql .= " WHERE b.fk_team = ?";
        $sql .= " ORDER BY lastname, firstname";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$this->rowid]);

        if($requete->rowCount() > 0)
        {
            $lines = $requete->fetchAll(PDO::FETCH_OBJ);

            foreach($lines as $line)
            {
                $User = new User();
                $User->initialize($line, true);
                $this->users[] = $User;
            }
        }

        if($depth > 1)
        {
            // fetch team / board columns
            $sql = "SELECT *";
            $sql .= " FROM storieshelper_map_column AS m";
            $sql .= " WHERE m.fk_team = ?";
            $sql .= " ORDER BY m.rank ASC";

            $requete = $this->getBdd()->prepare($sql);
            $requete->execute([$this->rowid]);

            if($requete->rowCount() > 0)
            {
                $lines = $requete->fetchAll(PDO::FETCH_OBJ);

                foreach($lines as $line)
                {
                    $MapColumn = new MapColumn();
                    $MapColumn->initialize($line);
                    $this->mapColumns[] = $MapColumn;  
                }
            }
        }
    }

    public function fetchMaxId()
    {
        $sql = "SELECT MAX(rowid) AS rowid";
        $sql .= " FROM storieshelper_team";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute();

        return $requete->fetch(PDO::FETCH_OBJ);
    }


    // INSERT

    public function create()
    {
        $sql = "INSERT INTO storieshelper_team (name, fk_project, active)";
        $sql .= " VALUES (?,?,?)";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$this->name, $this->fk_project, 1]);
        
        // get fk_team
        $sql = "SELECT MAX(rowid) AS rowid FROM storieshelper_team";
        $requete = $this->getBdd()->prepare($sql);
        $requete->execute();
        $fk_team = $requete->fetch(PDO::FETCH_OBJ)->rowid;

        $sql = "INSERT INTO storieshelper_map_column (name, fk_team, rank)";
        $sql .= " VALUES ('Open', ?, 0),('Ready', ?, 1),('In progress', ?, 2),('Closed', ?, 3)";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$fk_team, $fk_team, $fk_team, $fk_team]);

        return $fk_team;
    } 


    // UPDATE

    public function update()
    {
        $sql = "UPDATE storieshelper_team";
        $sql .= " SET";
        $sql .= " name = ?";
        $sql .= " ,fk_project = ?";
        $sql .= " ,active = ?";
        $sql .= " WHERE rowid = ?";

        $requete = $this->getBdd()->prepare($sql);
        return $requete->execute([$this->name,$this->fk_project,$this->active,$this->rowid]);
    }

    // DELETE

    public function delete()
    {
        $sql = "DELETE FROM storieshelper_team WHERE rowid = ?;";
        $sql .= "DELETE FROM storieshelper_belong_to WHERE fk_team = ?;";
        $sql .= "DELETE FROM storieshelper_task_comment WHERE fk_task IN (SELECT rowid FROM storieshelper_task WHERE fk_column IN (SELECT rowid FROM storieshelper_map_column WHERE fk_team = ?));";
        $sql .= "DELETE FROM storieshelper_task_member WHERE fk_task IN (SELECT rowid FROM storieshelper_task WHERE fk_column IN (SELECT rowid FROM storieshelper_map_column WHERE fk_team = ?));";
        $sql .= "DELETE FROM storieshelper_tasks WHERE fk_column IN (SELECT rowid FROM storieshelper_map_column WHERE fk_team = ?);";
        $sql .= "DELETE FROM storieshelper_map_column WHERE fk_team = ?;";
        
        $requete = $this->getBdd()->prepare($sql);
        return $requete->execute([$this->rowid,$this->rowid,$this->rowid,$this->rowid,$this->rowid,$this->rowid]);
    }

    // METHODS

    /**
     * Check if the task belongs to this Team
     * @param int fk_task the task to check
     * @return bool true OK, false KO
     */
    public function checkTask(int $fk_task)
    {
        foreach($this->mapColumns as $MapColumn)
        {
            foreach($MapColumn->getTasks() as $Task)
            {
                if($Task->getRowid() == $fk_task)
                {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Check if the taskComment belongs to this Team
     * @param int fk_comment the comment to check
     * @return bool true OK, false KO
     */
    public function checkTaskComment(int $fk_comment)
    {
        foreach($this->mapColumns as $MapColumn)
        {
            foreach($MapColumn->getTasks() as $Task)
            {
                foreach($Task->getComments() as $Comment)
                {
                    if($Comment->getRowid() == $fk_comment)
                    {
                        return true;
                    }
                }
            }
        }
        return false;
    }

    /**
     * Check if the user belongs to this Team
     * @param int fk_user the user to check
     * @return bool true OK, false KO
     */
    public function checkUser(int $fk_user)
    {
        foreach($this->users as $User)
        {
            if($User->getRowid() == $fk_user)
            {
                return true;
            }
        }
        return false;
    }

    /**
     * Check if the column belongs to this Team
     * @param int fk_column the column to check
     * @return bool true OK, false KO
     */
    public function checkColumn(int $fk_column)
    {
       foreach($this->mapColumns as $Column)
       {
           if($Column->getRowid() == $fk_column)
           {
               return true;
           }
       }
        return false;
    }
}