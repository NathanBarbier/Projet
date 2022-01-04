<?php Class Task extends Modele
{ 
    private $rowid;
    private $name;
    private $description;
    private $fk_column;
    private $rank;
    private $options = array();
    private $fk_author;
    private $admin;
    private $active;
    private $comments = array();

    public function __construct($taskId = null)
    {
        if($taskId != null)
        {
            $sql = "SELECT t.rowid, t.name, t.description, t.fk_column, t.rank, t.fk_author, t.admin, t.active";
            $sql .= " FROM tasks AS t";
            $sql .= " WHERE t.rowid = ?";

            $requete = $this->getBdd()->prepare($sql);
            $requete->execute([$taskId]);

            $Task = $requete->fetch(PDO::FETCH_OBJ);

            if($Task != false)
            {
                $this->rowid = $Task->rowid;
                $this->name = $Task->name;
                $this->description = $Task->description;
                $this->fk_column = $Task->fk_column;
                $this->rank = $Task->rank;
                $this->fk_author = $Task->fk_author;
                $this->admin = $Task->admin;
                $this->active = $Task->active;

                $sql = "SELECT tc.rowid, tc.fk_task, tc.note, tc.fk_user, tc.admin";
                $sql .= " FROM tasks_comments AS tc";
                $sql .= " WHERE tc.fk_task = ?";

                $requete = $this->getBdd()->prepare($sql);
                $requete->execute([$taskId]);

                $lines = $requete->fetchAll(PDO::FETCH_OBJ);

                foreach($lines as $obj)
                {
                    $this->comments = new TaskComment($obj->rowid);
                }
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

    public function setActive($active)
    {
        $this->active = $active;
    }

    
    // GETTER

    public function getRowid()
    {
        return $this->rowid;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getFk_column()
    {
        return $this->fk_column;
    }

    public function getFk_author()
    {
        return $this->fk_author;
    }

    public function getAdmin()
    {
        return $this->admin;
    }

    public function getActive()
    {
        return $this->active;
    }


    // CREATE

    public function create($fk_column, $fk_author, $admin = 0, string $name = null,string $description = null, $active = 1)
    {
        $sql = "SELECT MAX(rank) AS rank";
        $sql .= " FROM tasks";
        $sql .= " WHERE fk_column = ?";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$fk_column]);
        $obj = $requete->fetch(PDO::FETCH_OBJ);
        $rank = $obj->rank + 1;

        $sql = "INSERT INTO tasks (name, description, fk_column, rank, fk_author, admin, active)";
        $sql .= " VALUES (?,?,?,?,?,?,?)";

        $requete = $this->getBdd()->prepare($sql);
        return $requete->execute([$name, $description, $fk_column, $rank, $fk_author, $admin, $active]);
    }

    // FETCH

    public function fetch($taskId)
    {
        $sql = "SELECT t.rowid, t.name, t.description, t.fk_column, t.rank, t.fk_author, t.admin, t.active";
        $sql .= " FROM tasks AS t";
        $sql .= " WHERE rowid = ?";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$taskId]);

        return $requete->fetch(PDO::FETCH_OBJ);
    }

    public function fetchAll($fk_column)
    {
        $sql = "SELECT t.rowid, t.name, t.description, t.fk_column, t.rank, t.fk_author, t.admin, t.active";
        $sql .= " FROM tasks AS t";
        $sql .= " WHERE fk_column = ?";
        $sql .= " ORDER BY t.rank DESC";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$fk_column]);

        $lines = $requete->fetchAll(PDO::FETCH_OBJ);

        $Tasks = array();

        foreach($lines as $line)
        {
            $Tasks[] = new Task($line->rowid);
        }

        return $Tasks;
    }

    //* not sure to keep it
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

    //* not sure to keep it
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

    public function fetch_last_insert_id()
    {
        $sql = "SELECT MAX(rowid) AS rowid";
        $sql .= " FROM tasks";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute();

        return $requete->fetch(PDO::FETCH_OBJ);
    }

    public function fetchRank($rowid)
    {
        $sql = "SELECT rank";
        $sql .= " FROM tasks";
        $sql .= " WHERE rowid = ?";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$rowid]);

        return $requete->fetch(PDO::FETCH_OBJ)->rank;
    }

    public function fetchNextRank($rowid, $fk_column)
    {
        $sql = "SELECT t.rank AS nextRank, t.rowid AS rowid";
        $sql .= " FROM tasks AS t";
        $sql .= " WHERE t.fk_column = ?";
        $sql .= " AND t.rank > (SELECT rank FROM tasks WHERE rowid = ?)";
        $sql .= " ORDER BY t.rank ASC";
        $sql .= " LIMIT 1";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$fk_column, $rowid]);

        return $requete->fetch(PDO::FETCH_OBJ);
    }

    public function fetchPrevRank($rowid, $fk_column)
    {
        $sql = "SELECT t.rank AS prevRank, t.rowid AS rowid";
        $sql .= " FROM tasks AS t";
        $sql .= " WHERE t.fk_column = ?";
        $sql .= " AND t.rank < (SELECT rank FROM tasks WHERE rowid = ?)";
        $sql .= " ORDER BY t.rank DESC";
        $sql .= " LIMIT 1";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$fk_column, $rowid]);

        return $requete->fetch(PDO::FETCH_OBJ);
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

        $sql = "SELECT MAX(rank) AS rank";
        $sql .= " FROM tasks";
        $sql .= " WHERE fk_column = ?";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$fk_column]);

        $rank = $requete->fetch(PDO::FETCH_OBJ)->rank + 1;

        $sql = "UPDATE tasks";
        $sql .= " SET fk_column = ?,";
        $sql .= " rank = ?";
        $sql .= " WHERE rowid = ?";

        $requete = $this->getBdd()->prepare($sql);
        return $requete->execute([$fk_column, $rank, $rowid]);
    }

    public function updateRank($rank, $rowid = null)
    {
        $rowid = $rowid == null ? $this->rowid : $rowid;

        $sql = "UPDATE tasks";
        $sql .= " SET rank = ?";
        $sql .= " WHERE rowid = ?";

        $requete = $this->getBdd()->prepare($sql);
        return $requete->execute([$rank, $rowid]);
    }

    public function updateActive($active, $rowid = null)
    {
        $rowid = $rowid == null ? $this->rowid : $rowid;

        $sql = "UPDATE tasks";
        $sql .= " SET active = ?";
        $sql .= " WHERE rowid = ?";

        $requete = $this->getBdd()->prepare($sql);
        return $requete->execute([$active, $rowid]);
    }


    // DELETE

    public function delete($rowid = null)
    {
        $rowid = $rowid == null ? $this->rowid : $rowid;

        $sql = "DELETE FROM tasks";
        $sql .= " WHERE rowid = ?";

        $requete = $this->getBdd()->prepare($sql);
        return $requete->execute([$rowid]);
    }

    public function deleteByColumnId($fk_column)
    {
        $sql = "DELETE FROM tasks";
        $sql .= " WHERE fk_column = ?";

        $requete = $this->getBdd()->prepare($sql);
        return $requete->execute([$fk_column]);
    }

    
    // METHODS

    /**
     * Switch ranks between two tasks in two direction up or down
     * @param string $direction 'up' or 'down'
     */
    public function switchRank($rowid, $fk_column, $direction = 'up')
    {
        $status = array();

        $status[] = $rank = $this->fetchRank($rowid);
        if($direction == 'up')
        {
            $obj = $this->fetchNextRank($rowid, $fk_column);
            $otherRank = $obj->nextRank;
            $status[] = $obj;
        }
        else if($direction == 'down')
        {
            $obj = $this->fetchPrevRank($rowid, $fk_column);
            $otherRank = $obj->prevRank;
            $status[] = $obj;
        }

        $otherRowid = $obj->rowid;

        $status[] = $this->updateRank($otherRank, $rowid);
        $status[] = $this->updateRank($rank, $otherRowid);

        if(in_array(false, $status))
        {
            return false;
        }
        else
        {
            return true;
        }
    }

}
?>