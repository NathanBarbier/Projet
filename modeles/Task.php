<?php Class Task extends Modele
{ 
    private $rowid;
    private $name;
    private $description;
    private $fk_column;

    public function __construct($taskId = null)
    {

        if($taskId != null)
        {
            $sql = "SELECT t.rowid, t.name, t.description, t.fk_column";
            $sql .= " FROM tasks AS t";

            $requete = $this->getBdd()->prepare($sql);
            $requete->execute([$taskId]);

            $Task = $requete->fetch(PDO::FETCH_OBJ);

            $this->rowid = $Task->rowid;
            $this->name = $Task->name;
            $this->description = $Task->descritpion;
            $this->fk_column = $Task->fk_column;
        }
    }


    // SETTER

    public function setrowid($rowid)
    {
        $this->rowid = $rowid;
    }

    public function setname($name)
    {
        $this->name = $name;
    }

    public function setdescription($description)
    {
        $this->description = $description;
    }

    public function setfk_column($fk_column)
    {
        $this->fk_column = $fk_column;
    }

    
    // GETTER

    public function getrowid()
    {
        return $this->rowid;
    }

    public function getname()
    {
        return $this->name;
    }

    public function getdescription()
    {
        return $this->description;
    }

    public function getfk_column()
    {
        return $this->fk_column;
    }


    // FETCH

    public function fetch($taskId)
    {
        $sql = "SELECT t.rowid, t.name, t.description, t.fk_column";
        $sql .= " FROM tasks AS t";
        $sql .= " WHERE rowid = ?";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$taskId]);

        return $requete->fetch(PDO::FETCH_OBJ);
    }

    public function fetchCountTodo($fk_project)
    {
        $sql = "SELECT COUNT(t.rowid) AS counter";
        $sql .= " FROM tasks AS t";
        $sql .= " LEFT JOIN map_columns AS m ON m.rowId = t.fk_column";
        $sql .= " WHERE m.name = 'Ready' AND fk_project = ?";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$fk_project]);

        $CountTodo = $requete->fetch(PDO::FETCH_OBJ);

        return $CountTodo->counter;
    }

    public function fetchCountInProgress($fk_project)
    {
        $sql = "SELECT COUNT(t.rowid) AS counter";
        $sql .= " FROM tasks AS t";
        $sql .= " LEFT JOIN map_columns AS m ON m.rowId = t.fk_column";
        $sql .= " WHERE m.name = 'In progress' AND fk_project = ?";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$fk_project]);

        $CountInProgress = $requete->fetch(PDO::FETCH_OBJ);

        return $CountInProgress->counter;
    }



}
?>