<?php 
Class MapColumn extends Modele
{
    protected $rowid;
    protected $name;
    protected $tasks = array();
    protected $fk_team;
    protected $rank;

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
        $sql .= " FROM map_column AS m";
        $sql .= " WHERE m.rowid = ?";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$rowid]);

        $obj = $requete->fetch(PDO::FETCH_OBJ);

        $this->rowid = $obj->rowid;
        $this->name = $obj->name;
        $this->fk_team = $obj->fk_team;
        $this->rank = $obj->rank;

        $this->fetchTasks();
    }

    //* outdated
    public function fetchAll($fk_team)
    {
        $sql = "SELECT m.rowid, m.name, m.fk_team, m.rank";
        $sql .= " FROM map_column AS m";
        $sql .= " WHERE m.fk_team = ?";
        $sql .= " ORDER BY m.rank ASC";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$fk_team]);

        $lines = $requete->fetchAll(PDO::FETCH_OBJ);

        $MapColumns = array();

        foreach($lines as $line)
        {
            $MapColumns[] = new MapColumn($line->rowid);        
        }

        return $MapColumns;
    }

    public function fetchTasks()
    {
        $sql = "SELECT t.rowid";
        $sql .= " FROM task AS t";
        $sql .= " WHERE fk_column = ?";
        $sql .= " ORDER BY t.rank DESC";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$this->rowid]);

        $lines = $requete->fetchAll(PDO::FETCH_OBJ);

        foreach($lines as $line)
        {
            $this->tasks[] = new Task($line->rowid);
        }
    }

    public function fetch_last_insert_id()
    {
        $sql = "SELECT MAX(rowid) AS rowid";
        $sql .= " FROM map_column";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute();

        return $requete->fetch(PDO::FETCH_OBJ)->rowid;
    }

    public function fetchRank($rowid = null)
    {
        $rowid = $rowid == null ? $this->rowid : $rowid;

        $sql = "SELECT rank";
        $sql .= " FROM map_column";
        $sql .= " WHERE rowid = ?";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$rowid]);

        return $requete->fetch(PDO::FETCH_OBJ)->rank;
    }

    public function fetchNextRank($rowid, $fk_team)
    {
        $sql = "SELECT m.rank AS nextRank, rowid";
        $sql .= " FROM map_column AS m";
        $sql .= " WHERE fk_team = ?";
        $sql .= " AND m.rank > (SELECT rank FROM map_column WHERE rowid = ?)";
        $sql .= " ORDER BY m.rank ASC";
        $sql .= " LIMIT 1";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$fk_team, $rowid]);

        return $requete->fetch(PDO::FETCH_OBJ);
    }

    public function fetchPrevRank($rowid, $fk_team)
    {
        $sql = "SELECT m.rank AS prevRank, rowid";
        $sql .= " FROM map_column AS m";
        $sql .= " WHERE fk_team = ?";
        $sql .= " AND m.rank < (SELECT rank FROM map_column WHERE rowid = ?)";
        $sql .= " ORDER BY m.rank DESC";
        $sql .= " LIMIT 1";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$fk_team, $rowid]);

        return $requete->fetch(PDO::FETCH_OBJ);
    }


    // CREATE

    public function create()
    {
        $sql = "SELECT MAX(rank) AS rank";
        $sql .= " FROM map_column";
        $sql .= " WHERE fk_team = ".$this->fk_team."";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute();
        $obj = $requete->fetch(PDO::FETCH_OBJ);
        $rank = $obj->rank + 1;

        $sql = "INSERT INTO map_column (name, fk_team, rank)";
        $sql .= " VALUES ('".$this->name."',".$this->fk_team.",".$rank.")";
        
        $requete = $this->getBdd()->prepare($sql);
        return $requete->execute();
    }

    // UPDATE

    public function update()
    {
        $sql = "UPDATE map_column";
        $sql .= " SET ";
        $sql .= " name = '".$this->name."'";
        $sql .= " , fk_team = ".$this->fk_team;
        $sql .= " , rank = ".$this->rank;
        $sql .= " WHERE rowid = ".$this->rowid;

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute();
    }

    // DELETE

    public function delete()
    {
        $sql = "DELETE FROM task_comment";
        $sql .= " WHERE fk_task IN";
        $sql .= " (SELECT t.rowid";
        $sql .= " FROM tasks AS t";
        $sql .= " WHERE fk_column = ".$this->rowid.")";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute();

        $sql = "DELETE FROM task_member";
        $sql .= " WHERE fk_task IN";
        $sql .= " (SELECT t.rowid";
        $sql .= " FROM tasks AS t";
        $sql .= " WHERE fk_column = ".$this->rowid.")";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute();

        $sql = "DELETE FROM task";
        $sql .= " WHERE fk_column = ".$this->rowid;

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute();

        $sql = "DELETE FROM map_column";
        $sql .= " WHERE rowid = ".$this->rowid;

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute();
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
            $otherRank = $obj->nextRank;
        }
        else if($direction == 'left')
        {
            $obj = $this->fetchPrevRank($rowid, $fk_team);
            $otherRank = $obj->prevRank;
        }

        $otherRowid = $obj->rowid;

        $MapColumn = new MapColumn($rowid);
        $MapColumn->setRank($otherRank);
        $MapColumn->update();

        $MapColumn = new MapColumn($otherRowid);
        $MapColumn->setRank($rank);
        $MapColumn->update();
    }
}

?>