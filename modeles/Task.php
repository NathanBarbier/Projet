<?php Class Task extends Modele
{ 
    private $rowid;
    private $name;
    private $description;
    private $fk_column;
    private $options = array();

    public function __construct($taskId = null)
    {
        if($taskId != null)
        {
            $sql = "SELECT t.rowid, t.name, t.description, t.fk_column";
            $sql .= " FROM tasks AS t";
            $sql .= " WHERE fk_column = ?";

            $requete = $this->getBdd()->prepare($sql);
            $requete->execute([$taskId]);

            $Task = $requete->fetch(PDO::FETCH_OBJ);

            if($Task != false)
            {
                $this->rowid = $Task->rowid;
                $this->name = $Task->name;
                $this->description = $Task->description;
                $this->fk_column = $Task->fk_column;
            }
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


    // CREATE

    public function create(string $name,string $description, $fk_column)
    {
        $sql = "INSERT INTO tasks (name, description, fk_column)";
        $sql .= " VALUES (?,?,?)";

        $requete = $this->getBdd()->prepare($sql);
        return $requete->execute([$name, $description, $fk_column]);
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

    public function fetchAll($fk_column)
    {
        $sql = "SELECT t.rowid, t.name, t.description, t.fk_column";
        $sql .= " FROM tasks AS t";
        $sql .= " WHERE fk_column = ?";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$fk_column]);

        return $requete->fetchAll(PDO::FETCH_OBJ);
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

        if($CountTodo == false)
        {
            $CountTodo = new stdClass;
            $CountTodo->counter = 0;
        }

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

        if($CountInProgress == false)
        {
            $CountInProgress = new stdClass;
            $CountInProgress->counter = 0;
        }

        return $CountInProgress->counter;
    }


    // UPDATE

    public function updateName($name, $rowid = null)
    {
        $rowid = $rowid == null ? $this->rowid : $rowid;

        $sql = "UPDATE tasks";
        $sql .= " SET name = ?";
        $sql .= " WHERE rowid = ?";

        $requete = $this->getBdd()->prepare($sql);
        return $requete->execute([$name, $rowid]);
    }

    public function updateDescription($description, $rowid = null)
    {
        $rowid = $rowid == null ? $this->rowid : $rowid;

        $sql = "UPDATE tasks";
        $sql .= " SET description = ?";
        $sql .= " WHERE rowid = ?";

        $requete = $this->getBdd()->prepare($sql);
        return $requete->execute([$description, $rowid]);
    }

    public function updateFk_column($fk_column, $rowid = null)
    {
        $rowid = $rowid == null ? $this->rowid : $rowid;

        $sql = "UPDATE tasks";
        $sql .= " SET fk_column = ?";
        $sql .= " WHERE rowid = ?";

        $requete = $this->getBdd()->prepare($sql);
        return $requete->execute([$fk_column, $rowid]);
    }


    // DELETE

    public function delete($rowid = null)
    {
        $rowid = $rowid == null ? $this->rowid : $rowid;

        $sql = "DELETE FROM tasks";
        $sql = "WHERE rowid = ?";

        $requete = $this->getBdd()->prepare($sql);
        return $requete->execute([$rowid]);
    }

}
?>