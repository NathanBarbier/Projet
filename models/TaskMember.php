<?php 
Class TaskMember extends Modele 
{
    private $User;
    private $Task;

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

    public function setUser($User)
    {
        $this->User = $User;
    }

    public function setTask($Task)
    {
        $this->Task = $Task;
    }

    
    // GETTER

    public function getRowid()
    {
        return $this->rowid;
    }

    public function getUser()
    {
        return $this->User;
    }

    public function getTask()
    {
        return $this->Task;
    }


    // CREATE

    public function create($fk_user, $fk_task)
    {
        $sql = "INSERT INTO tasks_members";
        $sql .= " (fk_user, fk_task)";
        $sql .= " VALUES (?,?)";

        $requete = $this->getBdd()->prepare($sql);
        return $requete->execute([$fk_user, $fk_task]);
    }


    // UPDATE

    public function updateFk_user($fk_user, $rowid = null)
    {
        $rowid = $rowid == null ? $this->rowid : $rowid;

        $sql = "UPDATE tasks_members";
        $sql .= " SET fk_user = ?";
        $sql .= " WHERE rowid = ?";

        $requete = $this->getBdd()->prepare($sql);
        return $requete->execute([$fk_user, $rowid]);
    }

    public function updateFk_task($fk_task, $rowid = null)
    {
        $rowid = $rowid == null ? $this->rowid : $rowid;

        $sql = "UPDATE tasks_members";
        $sql .= " SET fk_task = ?";
        $sql .= " WHERE rowid = ?";

        $requete = $this->getBdd()->prepare($sql);
        return $requete->execute([$fk_task, $rowid]);
    }


    // DELETE

    public function delete($rowid = null)
    {
        $rowid = $rowid == null ? $this->rowid : $rowid;

        $sql = "DELETE FROM tasks_members";
        $sql .= " WHERE rowid = ?";

        $requete = $this->getBdd()->prepare($sql);
        return $requete->execute([$rowid]);
    }

    public function deleteByTaskIdAndUserId($fk_task, $fk_user)
    {
        $sql = "DELETE FROM tasks_members";
        $sql .= " WHERE fk_task = ? AND fk_user = ?";

        $requete = $this->getBdd()->prepare($sql);
        return $requete->execute([$fk_task, $fk_user]);
    }

    // FETCH

    public function fetchByTaskId(int $fk_task)
    {
        $sql = "SELECT t.fk_user";
        $sql .= " FROM tasks_members";
        $sql .= " WHERE t.fk_user = ?";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$fk_task]);

        $obj = $requete->fetch(PDO::FETCH_OBJ);

        $this->rowid = $obj->rowid;
        $this->User = new User($obj->fk_user);
        // $this->Task = new Task($obj->fk_task);
    }

    public function fetchAll($fk_task)
    {
        $sql = "SELECT t.rowid, t.fk_user, t.fk_task";
        $sql .= " FROM tasks_members AS t";
        $sql .= " WHERE fk_task = ?";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$fk_task]);

        return $requete->fetchAll(PDO::FETCH_OBJ);
    }
}
?>