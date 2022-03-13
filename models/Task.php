<?php Class Task extends Modele
{ 
    protected $rowid;
    protected $name;
    protected $description;
    protected $fk_column;
    protected $rank;
    protected $fk_user;
    protected $active;
    protected $created_at;
    protected $finished_at;
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

    public function setRowid($rowid)
    {
        $this->rowid = $rowid;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function setFk_column($fk_column)
    {
        $this->fk_column = $fk_column;
    }

    public function setRank($rank)
    {
        $this->rank = $rank;
    }

    public function setFk_user(int $fk_user)
    {
        $this->fk_user = $fk_user;
    }

    public function setActive(int $active)
    {
        $this->active = $active;
    }

    public function setComments($comments)
    {
        $this->comments = $comments;
    }

    public function setFinished_at($finished_at)
    {
        $this->finished_at = $finished_at;
    }

    public function setCreated_at($created_at)
    {
        $this->created_at = $created_at;
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

    public function getFk_user()
    {
        return $this->fk_user;
    }

    public function isActive()
    {
        return intval($this->active);
    }

    public function getComments()
    {
        return $this->comments;
    }

    public function getMembers()
    {
        return $this->members;
    }

    public function getCreated_at()
    {
        return $this->created_at;
    }

    public function getFinished_at()
    {
        return $this->finished_at;
    }


    // CREATE

    public function create()
    {
        $sql = "SELECT MAX(rank) AS rank";
        $sql .= " FROM storieshelper_task";
        $sql .= " WHERE fk_column = ?";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$this->fk_column]);
        $obj = $requete->fetch(PDO::FETCH_OBJ);
        $rank = $obj->rank + 1;

        $sql = "INSERT INTO storieshelper_task (fk_column, rank, fk_user, active, created_at)";
        $sql .= " VALUES (?,?,?,?,NOW())";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$this->fk_column,$rank,$this->fk_user,$this->active]);

        return $rank;
    }

    // FETCH

    public function fetch($rowid)
    {
        $sql = "SELECT *";
        $sql .= " FROM storieshelper_task";
        $sql .= " WHERE rowid = ?";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$rowid]);
        
        if($requete->rowCount() > 0)
        {
            $obj = $requete->fetch(PDO::FETCH_OBJ);

            $this->rowid            = $obj->rowid;
            $this->name             = $obj->name;
            $this->description      = $obj->description;
            $this->fk_column        = $obj->fk_column;
            $this->rank             = $obj->rank;
            $this->fk_user          = $obj->fk_user;
            $this->active           = $obj->active;
            $this->created_at       = $obj->created_at;
            $this->finished_at      = $obj->finished_at;
            $this->fetchComments();
            $this->fetchMembers();
        }
    }

    public function initialize(object $Obj)
    {
        $this->rowid        = $Obj->rowid;
        $this->name         = $Obj->name;
        $this->description  = $Obj->description;
        $this->fk_column    = $Obj->fk_column;
        $this->rank         = $Obj->rank;
        $this->fk_user      = $Obj->fk_user;
        $this->active       = $Obj->active;
        $this->created_at   = $Obj->created_at;
        $this->finished_at  = $Obj->finished_at;

        $this->fetchComments();
        $this->fetchMembers();
    }

    public function fetchComments()
    {
        $sql = "SELECT *";
        $sql .= " FROM storieshelper_task_comment";
        $sql .= " WHERE fk_task = ?";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$this->rowid]);

        if($requete->rowCount() > 0)
        {
            $lines = $requete->fetchAll(PDO::FETCH_OBJ);

            foreach($lines as $line)
            {
                $TaskComment = new TaskComment();
                $TaskComment->initialize($line);
                $this->comments[] = $TaskComment;
            }
        }
    }

    public function fetchMembers()
    {
        $sql = "SELECT u.rowid, u.firstname, u.lastname, u.birth, u.password, u.email, u.fk_organization, u.consent, u.admin, u.token";
        $sql .= " FROM storieshelper_task_member AS tm";
        $sql .= " INNER JOIN storieshelper_user AS u ON u.rowid = tm.fk_user";
        $sql .= " WHERE fk_task = ?";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$this->rowid]);

        if($requete->rowCount() > 0)
        {
            $lines = $requete->fetchAll(PDO::FETCH_OBJ);

            foreach($lines as $line)
            {
                $User = new User();
                $User->initialize($line);
                $this->members[] = $User;
            }
        }
    }

    public function fetch_last_insert_id()
    {
        $sql = "SELECT MAX(rowid) AS rowid";
        $sql .= " FROM storieshelper_task";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute();

        return $requete->fetch(PDO::FETCH_OBJ);
    }

    public function fetchRank($rowid)
    {
        $sql = "SELECT rank";
        $sql .= " FROM storieshelper_task";
        $sql .= " WHERE rowid = ?";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$rowid]);

        return $requete->fetch(PDO::FETCH_OBJ)->rank;
    }

    public function fetchNextRank($rowid, $fk_column)
    {
        $sql = "SELECT t.rank AS nextRank, t.rowid AS rowid";
        $sql .= " FROM storieshelper_task AS t";
        $sql .= " WHERE t.fk_column = ?";
        $sql .= " AND t.rank > (SELECT rank FROM storieshelper_task WHERE rowid = ?)";
        $sql .= " ORDER BY t.rank ASC";
        $sql .= " LIMIT 1";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$fk_column, $rowid]);

        if($requete->rowCount() > 0) {
            return $requete->fetch(PDO::FETCH_OBJ);
        } else {
            return false;
        }
    }

    public function fetchPrevRank($rowid, $fk_column)
    {
        $sql = "SELECT t.rank AS prevRank, t.rowid AS rowid";
        $sql .= " FROM storieshelper_task AS t";
        $sql .= " WHERE t.fk_column = ?";
        $sql .= " AND t.rank < (SELECT rank FROM storieshelper_task WHERE rowid = ?)";
        $sql .= " ORDER BY t.rank DESC";
        $sql .= " LIMIT 1";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$fk_column, $rowid]);

        if($requete->rowCount() > 0) {
            return $requete->fetch(PDO::FETCH_OBJ);
        } else {
            return false;
        }
    }


    // UPDATE

    public function update()
    {
        $sql = "UPDATE storieshelper_task";
        $sql .= " SET";
        $sql .= " name = ?";
        $sql .= " , description = ?";
        $sql .= " , fk_column = ?";
        $sql .= " , rank = ?";
        $sql .= " , active = ?";
        $sql .= " , finished_at = ?";
        $sql .= " , created_at = ?";
        $sql .= " WHERE rowid = ?";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$this->name,$this->description,$this->fk_column,$this->rank,$this->active,$this->finished_at,$this->created_at,$this->rowid]);
    }

    // DELETE

    public function delete()
    {
        $sql = "DELETE FROM storieshelper_task_comment";
        $sql .= " WHERE fk_task = ?;";

        $sql .= "DELETE FROM storieshelper_task_member";
        $sql .= " WHERE fk_task = ?;";

        $sql .= "DELETE FROM storieshelper_task";
        $sql .= " WHERE rowid = ?;";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$this->rowid,$this->rowid,$this->rowid]);
    }
    
    // METHODS

    /**
     * Switch ranks between two tasks in two direction up or down
     * @param string $direction 'up' or 'down'
     */
    public function switchRank($rowid, $fk_column, $direction = 'up')
    {
        $rank = $this->fetchRank($rowid);
        if($direction == 'up')
        {
            $obj = $this->fetchNextRank($rowid, $fk_column);
            if($obj) {
                $otherRank = $obj->nextRank;
            }
        }
        else if($direction == 'down')
        {
            $obj = $this->fetchPrevRank($rowid, $fk_column);
            if($obj) {
                $otherRank = $obj->prevRank;
            }
        }

        if(!empty($otherRank))
        {
            $otherRowid = $obj->rowid;
    
            $Task = new Task($rowid);
            $Task->setRank($otherRank);
            $Task->update();
    
            $Task = new Task($otherRowid);
            $Task->setRank($rank);
            $Task->update();
        }
    }

}
?>