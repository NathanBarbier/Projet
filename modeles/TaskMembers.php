<?php 
Class TaskMembers extends Modele 
{
    private $rowid;
    private $fk_user;
    private $fk_task;

    public function __construct($rowid = null)
    {
        if($rowid != null)
        {
            $sql = "SELECT t.rowid, t.fk_user, t.fk_task";
            $sql .= " FROM tasks_members";
            $sql .= " WHERE t.rowid = ?";

            $requete = $this->getBdd()->prepare($sql);
            $requete->execute([$rowid]);

            $line = $requete->fetch(PDO::FETCH_OBJ);

            $this->rowid = $line->rowid;
            $this->fk_user = $line->fk_user;
            $this->fk_task = $line->fk_task;
        }
    }


    // SETTER
    public function setRowid($rowid)
    {
        $this->rowid = $rowid;
    }

    public function setFk_user($fk_user)
    {
        $this->fk_user = $fk_user;
    }

    public function setFk_task($fk_task)
    {
        $this->fk_task = $fk_task;
    }

    
    // GETTER

    public function getRowid()
    {
        return $this->rowid;
    }

    public function getFk_user()
    {
        return $this->fk_user;
    }

    public function getFk_task()
    {
        return $this->fk_task;
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

    public function fetch($rowid)
    {
        $sql = "SELECT t.rowid, t.fk_user, t.fk_task";
        $sql .= " FROM tasks_members AS t";
        $sql .= " WHERE rowid = ?";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$rowid]);

        return $requete->fetch(PDO::FETCH_OBJ);
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