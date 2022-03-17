<?php 
Class MapColumn extends Modele
{
    protected int $rowid;
    protected string $name;
    protected array $tasks;
    protected int $fk_team;
    protected int $rank;

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

    public function setFk_team(int $fk_team)
    {
        $this->fk_team = $fk_team;
    }

    public function setRank($rank)
    {
        $this->rank = $rank;
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

    public function getFk_team()
    {
        return $this->fk_team;
    }

    public function getTasks()
    {
        return $this->tasks;
    }

    public function getActiveTasks()
    {
        $activeTasks = array();
        foreach($this->tasks as $Task)
        {
            if($Task->isActive())
            {
                $activeTasks[] = $Task;
            }
        }

        return $activeTasks;
    }

    public function getRank()
    {
        return $this->rank;
    }

    // FETCH

    public function fetch(int $rowid)
    {
        $sql = "SELECT m.rowid, m.name, m.fk_team, m.rank";
        $sql .= " FROM storieshelper_map_column AS m";
        $sql .= " WHERE m.rowid = ?";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$rowid]);

        if($requete->rowCount() > 0)
        {
            $obj = $requete->fetch(PDO::FETCH_OBJ);
    
            $this->rowid        = $obj->rowid;
            $this->name         = $obj->name;
            $this->fk_team      = $obj->fk_team;
            $this->rank         = $obj->rank;
    
            $this->fetchTasks();
        }
    }

    public function initialize($Obj)
    {
        $this->rowid        = $Obj->rowid;
        $this->name         = $Obj->name;
        $this->fk_team      = $Obj->fk_team;
        $this->rank         = $Obj->rank;

        $this->fetchTasks();
    }

    public function fetchTasks()
    {
        $sql = "SELECT *";
        $sql .= " FROM storieshelper_task";
        $sql .= " WHERE fk_column = ?";
        $sql .= " ORDER BY rank DESC";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$this->rowid]);

        if($requete->rowCount() > 0)
        {
            $lines = $requete->fetchAll(PDO::FETCH_OBJ);
    
            foreach($lines as $line)
            {
                $Task = new Task();
                $Task->initialize($line);
                $this->tasks[] = $Task;
            }
        }
    }

    public function fetch_last_insert_id($idTeam)
    {
        $sql = "SELECT MAX(rowid) AS rowid";
        $sql .= " FROM storieshelper_map_column";
        $sql .= " WHERE fk_team = ?";
        
        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$idTeam]);

        return $requete->fetch(PDO::FETCH_OBJ)->rowid;
    }

    public function fetchRank($rowid = null)
    {
        $rowid = $rowid == null ? $this->rowid : $rowid;

        $sql = "SELECT rank";
        $sql .= " FROM storieshelper_map_column";
        $sql .= " WHERE rowid = ?";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$rowid]);

        return $requete->fetch(PDO::FETCH_OBJ)->rank;
    }

    public function fetchNextRank($rowid, $fk_team)
    {
        $sql = "SELECT m.rank AS nextRank, m.name AS name, rowid";
        $sql .= " FROM storieshelper_map_column AS m";
        $sql .= " WHERE fk_team = ?";
        $sql .= " AND name <> 'Closed'";
        $sql .= " AND m.rank > (SELECT rank FROM storieshelper_map_column WHERE rowid = ?)";
        $sql .= " ORDER BY m.rank ASC";
        $sql .= " LIMIT 1";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$fk_team, $rowid]);

        if($requete->rowCount() > 0) {
            return $requete->fetch(PDO::FETCH_OBJ);
        } else {
            return false;
        }
    }

    public function fetchPrevRank($rowid, $fk_team)
    {
        $sql = "SELECT m.rank AS prevRank, m.name AS name, rowid";
        $sql .= " FROM storieshelper_map_column AS m";
        $sql .= " WHERE fk_team = ?";
        $sql .= " AND name <> 'Open'";
        $sql .= " AND m.rank < (SELECT rank FROM storieshelper_map_column WHERE rowid = ?)";
        $sql .= " ORDER BY m.rank DESC";
        $sql .= " LIMIT 1";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$fk_team, $rowid]);

        if($requete->rowCount() > 0) {
            return $requete->fetch(PDO::FETCH_OBJ);
        } else {
            return false;
        }
    }
    
    public function fetchFinishedColumn(int $fk_team)
    {
        $sql = "SELECT *";
        $sql .= " FROM storieshelper_map_column";
        $sql .= " WHERE fk_team = ?";
        $sql .= " AND name = 'Closed'";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$fk_team]);

        return $requete->fetch(PDO::FETCH_ASSOC);
    }

    // CREATE

    public function create()
    {
        $sql = "SELECT MAX(rank) AS rank";
        $sql .= " FROM storieshelper_map_column";
        $sql .= " WHERE fk_team = ?";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$this->fk_team]);
        $obj = $requete->fetch(PDO::FETCH_OBJ);
        $rank = $obj->rank + 1;

        $sql = "INSERT INTO storieshelper_map_column (name, fk_team, rank)";
        $sql .= " VALUES (?,?,?)";
        
        $requete = $this->getBdd()->prepare($sql);
        return $requete->execute([$this->name, $this->fk_team, $rank]);
    }

    // UPDATE

    public function update()
    {
        $sql = "UPDATE storieshelper_map_column";
        $sql .= " SET ";
        $sql .= " name = ?";
        $sql .= " , fk_team = ?";
        $sql .= " , rank = ?";
        $sql .= " WHERE rowid = ?";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$this->name,$this->fk_team,$this->rank,$this->rowid]);
    }

    public function updateClosedColumn($teamId, $rank)
    {
        $closedColumn = $this->fetchFinishedColumn($teamId);
        
    }

    // DELETE

    public function delete()
    {
        $sql = "DELETE FROM storieshelper_task_comment";
        $sql .= " WHERE fk_task IN";
        $sql .= " (SELECT t.rowid";
        $sql .= " FROM storieshelper_task AS t";
        $sql .= " WHERE fk_column = ?);";

        $sql .= "DELETE FROM storieshelper_task_member";
        $sql .= " WHERE fk_task IN";
        $sql .= " (SELECT t.rowid";
        $sql .= " FROM storieshelper_task AS t";
        $sql .= " WHERE fk_column = ?);";

        $sql .= "DELETE FROM storieshelper_task";
        $sql .= " WHERE fk_column = ?;";

        $sql .= "DELETE FROM storieshelper_map_column";
        $sql .= " WHERE rowid = ?;";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$this->rowid,$this->rowid,$this->rowid,$this->rowid]);
    }
    
    // METHODS

    /**
     * Switch ranks between two coulmns between two direction left or right
     * @param int $rowid fk_column
     * @param int $fk_team
     * @param string $direction 'left' or 'right'
     */
    public function switchRank($rowid, $fk_team, $direction = 'left')
    {
        $rank = $this->fetchRank($rowid);
        if($direction == 'right')
        {
            $obj = $this->fetchNextRank($rowid, $fk_team);
            if($obj) {
                $otherRank = $obj->nextRank;
            }
        }
        else if($direction == 'left')
        {
            $obj = $this->fetchPrevRank($rowid, $fk_team);
            if($obj) {
                $otherRank = $obj->prevRank;
            }
        }

        if(!empty($otherRank))
        {
            $otherRowid = $obj->rowid;
    
            $MapColumn = new MapColumn($rowid);
            $MapColumn->setRank($otherRank);
            $MapColumn->update();
    
            $MapColumn = new MapColumn($otherRowid);
            $MapColumn->setRank($rank);
            $MapColumn->update();
            return true;
        } else {
            return false;
        }
    }
}

?>