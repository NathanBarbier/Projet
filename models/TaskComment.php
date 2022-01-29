<?php 
class TaskComment extends Modele 
{
    protected $rowid;
    protected $fk_task;
    protected $note;
    protected $fk_user;

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

    // public function setTask(Task $Task)
    // {
    //     $this->Task = $Task;
    // }
    public function setFk_task(int $fk_task)
    {
        $this->fk_task = $fk_task;
    }

    public function setNote(string $note)
    {
        $this->note = $note;
    }

    // public function setUser(User $User)
    // {
    //     $this->User = $User;
    // }
    public function setFk_user(int $fk_user)
    {
        $this->fk_user = $fk_user;
    }

    // GETTER

    public function getRowid()
    {
        return $this->rowid;
    }

    // public function getTask()
    // {
    //     return $this->Task;
    // }
    public function getFk_task()
    {
        return $this->fk_task;
    }

    public function getNote()
    {
        return $this->note;
    }

    // public function getUser()
    // {
    //     return $this->User;
    // }
    public function getFk_user()
    {
        return $this->fk_user;
    }


    // CREATE

    public function create()
    {
        $sql = "INSERT INTO task_comment";
        $sql .= " (fk_task, note, fk_user)";
        $sql .= " VALUES (".$this->fk_task.",'".$this->note."',".$this->fk_user.")";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute();

        return $this->fetch_last_insert_id();
    }


    // UPDATE

    public function update()
    {
        $sql = "UPDATE task_comment";
        $sql .= " SET note = ?";
        $sql .= " WHERE rowid = ?";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$this->note,$this->rowid]);
    }


    // DELETE

    public function delete($rowid = null)
    {
        $rowid = $rowid == null ? $this->rowid : $rowid;

        $sql = "DELETE FROM task_comment";
        $sql .= " WHERE rowid = ?";

        $requete = $this->getBdd()->prepare($sql);
        return $requete->execute([$rowid]);
    }

    public function deleteByColumnId($fk_column)
    {
        $sql = "DELETE FROM task_comment";
        $sql .= " WHERE fk_task IN";
        $sql .= " (SELECT t.rowid";
        $sql .= " FROM tasks AS t";
        $sql .= " WHERE fk_column = ?)";

        $requete = $this->getBdd()->prepare($sql);
        return $requete->execute([$fk_column]);
    }

    public function deleteByTaskId($fk_task)
    {
        $sql = "DELETE FROM task_comment";
        $sql .= " WHERE fk_task = ?";

        $requete = $this->getBdd()->prepare($sql);
        return $requete->execute([$fk_task]);
    }


    // FETCH

    public function fetch($rowid = null)
    {
        $rowid = $rowid == null ? $this->rowid : $rowid;

        $sql = "SELECT t.rowid, t.fk_task, t.note, t.fk_user";
        $sql .= " FROM task_comment AS t";
        $sql .= " WHERE rowid = ?";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$rowid]);

        if($requete->rowCount() > 0)
        {
            $obj = $requete->fetch(PDO::FETCH_OBJ);

            $this->rowid = intval($rowid);
            // $this->Task = new Task($obj->fk_task);
            $this->fk_task = intval($obj->fk_task);
            $this->note = $obj->note;
            // $this->User = new User($obj->fk_user);
            $this->fk_user = intval($obj->fk_user);
        }
    }

    public function fetch_last_insert_id()
    {
        $sql = "SELECT MAX(rowid) AS rowid";
        $sql .= " FROM task_comment";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute();

        return $requete->fetch(PDO::FETCH_OBJ)->rowid;
    }
}
?>