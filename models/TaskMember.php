<?php 
Class TaskMember extends Modele 
{
    protected $rowid;
    protected $fk_user;
    protected $fk_task;

    public function __construct($fk_task = null)
    {
        if($fk_task != null)
        {
            $this->fetchByTaskId($fk_task);
        }
    }


    // SETTER
    public function setRowid($rowid)
    {
        $this->rowid = $rowid;
    }

    // public function setUser($User)
    // {
    //     $this->User = $User;
    // }

    // public function setTask($Task)
    // {
    //     $this->Task = $Task;
    // }

    public function setFk_user(int $fk_user)
    {
        $this->fk_user = $fk_user;
    }

    public function setFk_task(int $fk_task)
    {
        $this->fk_task = $fk_task;
    }

    
    // GETTER

    public function getRowid()
    {
        return $this->rowid;
    }

    // public function getUser()
    // {
    //     return $this->User;
    // }

    // public function getTask()
    // {
    //     return $this->Task;
    // }

    public function getFk_user()
    {
        return $this->fk_user;
    }

    public function getFk_task()
    {
        return $this->fk_task;
    }


    // CREATE

    public function create()
    {
        $sql = "INSERT INTO storieshelper_task_member";
        $sql .= " (fk_user, fk_task)";
        $sql .= " VALUES (?,?)";

        $requete = $this->getBdd()->prepare($sql);
        return $requete->execute([$this->fk_user,$this->fk_task]);
    }

    // DELETE

    public function delete()
    {
        $sql = "DELETE FROM storieshelper_task_member";
        $sql .= " WHERE fk_task = ".$this->fk_task." AND fk_user = ".$this->fk_user;

        $requete = $this->getBdd()->prepare($sql);
        return $requete->execute();
    }

    // FETCH

    public function fetchByTaskId(int $fk_task)
    {
        $sql = "SELECT t.fk_user";
        $sql .= " FROM storieshelper_task_member AS t";
        $sql .= " WHERE t.fk_task = ?";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$fk_task]);

        if($requete->rowCount() > 0)
        {
            $obj = $requete->fetch(PDO::FETCH_OBJ);
    
            $this->rowid    = $obj->rowid;
            $this->fk_user  = $obj->fk_user;
            $this->fk_task  = $fk_task;
        }
    }
}
?>