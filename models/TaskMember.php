<?php 
Class TaskMember extends Modele 
{
    protected ?int $fk_user = null;
    protected ?int $fk_task = null;

    public function __construct($fk_user = null, $fk_task = null)
    {
        if($fk_user != null && $fk_task != null)
        {
            $this->fetch($fk_user, $fk_task);
        }
    }


    // SETTER

    public function setFk_user(int $fk_user)
    {
        $this->fk_user = $fk_user;
    }

    public function setFk_task(int $fk_task)
    {
        $this->fk_task = $fk_task;
    }

    
    // GETTER

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
        $sql .= " WHERE fk_task = ? AND fk_user = ?";

        $requete = $this->getBdd()->prepare($sql);
        return $requete->execute([$this->fk_task, $this->fk_user]);
    }

    // FETCH

    public function fetch(int $fk_user, int $fk_task)
    {
        $sql = "SELECT *";
        $sql .= " FROM storieshelper_task_member";
        $sql .= " WHERE fk_user = ? AND fk_task = ?";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$fk_user, $fk_task]);

        if($requete->rowCount() > 0)
        {
            $obj = $requete->fetch(PDO::FETCH_OBJ);
    
            $this->fk_user  = $obj->fk_user;
            $this->fk_task  = $obj->fk_task;
        }
    }
}
?>