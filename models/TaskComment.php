<?php 
class TaskComment extends Modele 
{
    protected ?int $rowid = null;
    protected ?int $fk_task = null;
    protected ?string $note = null;
    protected ?int $fk_user = null;
    protected ?string $tms = null;

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

    public function setFk_task(int $fk_task)
    {
        $this->fk_task = $fk_task;
    }

    public function setNote(string $note)
    {
        $this->note = $note;
    }

    public function setFk_user(int $fk_user)
    {
        $this->fk_user = $fk_user;
    }

    // GETTER

    public function getRowid(): ?int
    {
        return $this->rowid;
    }

    public function getFk_task(): ?int
    {
        return $this->fk_task;
    }

    public function getNote(): ?string
    {
        return $this->note;
    }

    public function getFk_user(): ?int
    {
        return $this->fk_user;
    }


    // CREATE

    public function create()
    {
        $date = new DateTime();
        $tms = $date->getTimestamp();

        $sql = "INSERT INTO storieshelper_task_comment";
        $sql .= " (fk_task, note, fk_user, tms)";
        $sql .= " VALUES (?,?,?,?)";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$this->fk_task,$this->note,$this->fk_user,$tms]);

        return $this->fetch_last_insert_id();
    }


    // UPDATE

    public function update()
    {
        $sql = "UPDATE storieshelper_task_comment";
        $sql .= " SET note = ?";
        $sql .= " WHERE rowid = ?";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$this->note,$this->rowid]);
    }


    // DELETE

    public function delete()
    {
        $sql = "DELETE FROM storieshelper_task_comment";
        $sql .= " WHERE rowid = ?";

        $requete = $this->getBdd()->prepare($sql);
        return $requete->execute([$this->rowid]);
    }


    // FETCH

    public function fetch($rowid = null)
    {
        $rowid = $rowid == null ? $this->rowid : $rowid;

        $sql = "SELECT t.rowid, t.fk_task, t.note, t.fk_user, t.tms";
        $sql .= " FROM storieshelper_task_comment AS t";
        $sql .= " WHERE rowid = ?";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$rowid]);

        if($requete->rowCount() > 0)
        {
            $obj = $requete->fetch(PDO::FETCH_OBJ);

            $this->rowid    = intval($rowid);
            $this->fk_task  = intval($obj->fk_task);
            $this->note     = $obj->note;
            $this->fk_user  = intval($obj->fk_user);

            if(!empty($obj->tms)) 
            {
                $date = new DateTime();
                $date->setTimestamp($obj->tms);
                $date->setTimezone(new DateTimeZone('Europe/Paris'));
                $this->tms = $date->format('d/m/y H:i');
            }
        }
    }

    public function initialize(object $Obj)
    {
        $this->rowid    = intval($Obj->rowid);
        $this->fk_task  = intval($Obj->fk_task);
        $this->note     = $Obj->note;
        $this->fk_user  = intval($Obj->fk_user);
        
        if(!empty($Obj->tms)) 
        {
            $date = new DateTime();
            $date->setTimestamp($Obj->tms);
            $date->setTimezone(new DateTimeZone('Europe/Paris'));
            $this->tms = $date->format('d/m/y H:i');
        }
    }

    public function fetch_last_insert_id()
    {
        $sql = "SELECT MAX(rowid) AS rowid";
        $sql .= " FROM storieshelper_task_comment";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute();

        return $requete->fetch(PDO::FETCH_OBJ)->rowid;
    }
}
?>