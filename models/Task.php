<?php Class Task extends Modele
{ 
    protected $rowid;
    protected $name;
    protected $description;
    protected $MapColumn;
    protected $rank;
    // private $options = array();
    protected $Author; // task author - User()
    protected $admin;
    protected $active;
    protected $members = array();
    protected $comments = array();

    public function __construct($rowid = null)
    {
        if($rowid != null)
        {
            $this->fetch($rowid);
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

    public function setRank($rank)
    {
        $this->rank = $rank;
    }

    public function setAuthor(User $Author)
    {
        $this->Author = $Author;
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
        return $this->Author;
    }

    public function getAdmin()
    {
        return $this->admin;
    }

    public function isActive()
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

    public function fetch($rowid)
    {
        $sql = "SELECT t.rowid, t.name, t.description, t.fk_column, t.rank, t.fk_author, t.admin, t.active";
        $sql .= " FROM tasks AS t";
        $sql .= " WHERE t.rowid = ?";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$rowid]);
        
        if($requete->rowCount() > 0)
        {
            $obj = $requete->fetch(PDO::FETCH_OBJ);

            $this->rowid = $obj->rowid;
            $this->name = $obj->name;
            $this->description = $obj->description;
            $this->fk_column = new MapColumn($obj->fk_column);
            $this->rank = $obj->rank;
            $this->Author = new User($obj->fk_author);
            $this->admin = $obj->admin;
            $this->active = $obj->active;
            $this->fetchComments();
        }
    }

    public function fetchComments()
    {
        $sql = "SELECT tc.rowid";
        $sql .= " FROM tasks_comments AS tc";
        $sql .= " WHERE tc.fk_task = ?";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$this->rowid]);

        if($requete->rowCount() > 0)
        {
            $lines = $requete->fetchAll(PDO::FETCH_OBJ);

            foreach($lines as $line)
            {
                $this->comments[] = new TaskComment($line->rowid);
            }
        }
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