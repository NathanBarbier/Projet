<?php 
class TaskComment extends Modele 
{
    private $rowid;
    private $fk_task;
    private $note;
    private $fk_user;

    public function __construct($rowid = null)
    {
        if($rowid != null)
        {
            $sql = "SELECT t.fk_task, t.note, t.fk_user";
            $sql .= " FROM tasks_comments AS t";
            $sql .= " WHERE t.rowid = ?";

            $requete = $this->getBdd()->prepare($sql);
            $requete->execute([$rowid]);

            $obj = $requete->fetch(PDO::FETCH_OBJ);

            $this->rowid = $rowid;
            $this->fk_task = $obj->fk_task;
            $this->note = $obj->note;
            $this->fk_user = $obj->fk_user;
        }
    }


    // SETTER

    public function setRowid($rowid)
    {
        $this->rowid = $rowid;
    }

    public function setFk_task($fk_task)
    {
        $this->fk_task = $fk_task;
    }

    public function setNote($note)
    {
        $this->note = $note;
    }

    public function setFk_user($fk_user)
    {
        $this->fk_user = $fk_user;
    }


    // GETTER

    public function getRowid()
    {
        return $this->rowid;
    }

    public function getFk_task()
    {
        return $this->fk_task;
    }

    public function getNote()
    {
        return $this->note;
    }

    public function getFk_user()
    {
        return $this->fk_user;
    }


    // CREATE

    public function create($fk_task, $fk_user, $note = null)
    {
        $sql = "INSERT INTO tasks_comments";
        $sql .= " (fk_task, note, fk_user)";
        $sql .= " VALUES (?,?,?)";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$fk_task, $note, $fk_user]);

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


    // FETCH

    public function fetch($rowid = null)
    {
        $rowid = $rowid == null ? $this->rowid : $rowid;

        $sql = "SELECT t.rowid, t.fk_task, t.note, t.fk_user";
        $sql .= " FROM tasks_comments AS t";
        $sql .= " WHERE rowid = ?";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$rowid]);

        return $requete->fetch(PDO::FETCH_OBJ);
    }

    public function fetchAll($fk_task)
    {
        $sql = "SELECT t.rowid, t.fk_task, t.note, t.fk_user";
        $sql .= " FROM tasks_comments AS t";
        $sql .= " WHERE fk_task = ?";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$fk_task]);

        return $requete->fetchAll(PDO::FETCH_OBJ);
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