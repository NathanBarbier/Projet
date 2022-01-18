<?php 
class TaskComment extends Modele 
{
    protected $rowid;
    protected $Task;
    protected $note;
    protected $User;

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

    public function setTask(Task $Task)
    {
        $this->Task = $Task;
    }

    public function setNote($note)
    {
        $this->note = $note;
    }

    public function setUser(User $User)
    {
        $this->User = $User;
    }


    // GETTER

    public function getRowid()
    {
        return $this->rowid;
    }

    public function getTask()
    {
        return $this->Task;
    }

    public function getNote()
    {
        return $this->note;
    }

    public function getUser()
    {
        return $this->User;
    }


    // CREATE

    public function create($fk_task, $fk_user, $note = null, $admin = 0)
    {
        $sql = "INSERT INTO tasks_comments";
        
        if($note != null)
        {
            $sql .= " (fk_task, note, fk_user, admin)";
            $sql .= " VALUES (?,?,?,?)";
            $requete = $this->getBdd()->prepare($sql);
            $requete->execute([$fk_task, $note, $fk_user, $admin]);
        }
        else
        {
            $sql .= " (fk_task, fk_user, admin)";
            $sql .= " VALUES (?,?,?)";
            $requete = $this->getBdd()->prepare($sql);
            $requete->execute([$fk_task, $fk_user, $admin]);
        }

        return $this->fetch_last_insert_id();
    }


    // UPDATE

    public function updateNote($note, $rowid = null)
    {
        $rowid = $rowid == null ? $this->rowid : $rowid;

        $sql = "UPDATE tasks_comments";
        $sql .= " SET note = ?";
        $sql .= " WHERE rowid = ?";

        $requete = $this->getBdd()->prepare($sql);
        return $requete->execute([$note, $rowid]);
    }


    // DELETE

    public function delete($rowid = null)
    {
        $rowid = $rowid == null ? $this->rowid : $rowid;

        $sql = "DELETE FROM tasks_comments";
        $sql .= " WHERE rowid = ?";

        $requete = $this->getBdd()->prepare($sql);
        return $requete->execute([$rowid]);
    }

    public function deleteByColumnId($fk_column)
    {
        $sql = "DELETE FROM tasks_comments";
        $sql .= " WHERE fk_task IN";
        $sql .= " (SELECT t.rowid";
        $sql .= " FROM tasks AS t";
        $sql .= " WHERE fk_column = ?)";

        $requete = $this->getBdd()->prepare($sql);
        return $requete->execute([$fk_column]);
    }

    public function deleteByTaskId($fk_task)
    {
        $sql = "DELETE FROM tasks_comments";
        $sql .= " WHERE fk_task = ?";

        $requete = $this->getBdd()->prepare($sql);
        return $requete->execute([$fk_task]);
    }


    // FETCH

    public function fetch($rowid = null)
    {
        $rowid = $rowid == null ? $this->rowid : $rowid;

        $sql = "SELECT t.rowid, t.fk_task, t.note, t.fk_user";
        $sql .= " FROM tasks_comments AS t";
        $sql .= " WHERE rowid = ?";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$rowid]);

        if($requete->rowCount() > 0)
        {
            $obj = $requete->fetch(PDO::FETCH_OBJ);

            $this->rowid = $rowid;
            $this->Task = new Task($obj->fk_task);
            $this->note = $obj->note;
            $this->User = new User($obj->fk_user);
        }
    }

    public function fetch_last_insert_id()
    {
        $sql = "SELECT MAX(rowid) AS rowid";
        $sql .= " FROM tasks_comments";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute();

        return $requete->fetch(PDO::FETCH_OBJ)->rowid;
    }
}
?>