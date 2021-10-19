<?php 
Class MapColumns extends Modele
{
    private $rowid;
    private $name;
    private $tasks = array();
    private $rank;

    public function __construct($rowid = null)
    {
        if($rowid != null)
        {
            $sql = "SELECT m.rowid, m.name, m.fk_team, m.rank";
            $sql .= " FROM map_columns AS m";
            $sql .= " WHERE m.rowid = ?";

            $requete = $this->getBdd()->prepare($sql);
            $requete->execute([$rowid]);

            $Column = $requete->fetch(PDO::FETCH_OBJ);

            $this->rowid = $Column->rowid;
            $this->name = $Column->name;
            $this->fk_team = $Column->fk_team;
            $this->rank = $Column->rank;

            $Task = new Task();
            $lines = $Task->fetchAll($this->rowid);

            foreach($lines as $line)
            {
                $Task = new Task($line->rowid);
                $this->tasks[] = $Task;
            }
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

    public function setfk_team($fk_team)
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

    public function getfk_team()
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
        foreach($this->tasks as $task)
        {
            if($task->getActive() == 1)
            {
                $activeTasks[] = $task;
            }
        }

        return $activeTasks;
    }

    public function getRank()
    {
        return $this->rank;
    }

    // FETCH

    public function fetch($rowid)
    {
        $sql = "SELECT m.rowid, m.name, m.fk_team, m.rank";
        $sql .= " FROM map_columns AS m";
        $sql .= " WHERE m.rowid = ?";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$rowid]);

        return $requete->fetch(PDO::FETCH_OBJ);
    }

    public function fetchAll($fk_team)
    {
        $sql = "SELECT m.rowid, m.name, m.fk_team, m.rank";
        $sql .= " FROM map_columns AS m";
        $sql .= " WHERE m.fk_team = ?";
        $sql .= " ORDER BY m.rank ASC";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$fk_team]);

        return $requete->fetchAll(PDO::FETCH_OBJ);
    }

    public function fetch_last_insert_id()
    {
        $sql = "SELECT MAX(rowid) AS rowid";
        $sql .= " FROM map_columns";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute();

        return $requete->fetch(PDO::FETCH_OBJ);
    }

    public function fetchRank($rowid = null)
    {
        $rowid = $rowid == null ? $this->rowid : $rowid;

        $sql = "SELECT rank";
        $sql .= " FROM map_columns";
        $sql .= " WHERE rowid = ?";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$rowid]);

        return $requete->fetch(PDO::FETCH_OBJ)->rank;
    }

    public function fetchNextRank($rowid, $fk_team)
    {
        $sql = "SELECT m.rank AS nextRank, rowid";
        $sql .= " FROM map_columns AS m";
        $sql .= " WHERE fk_team = ?";
        $sql .= " AND m.rank > (SELECT rank FROM map_columns WHERE rowid = ?)";
        $sql .= " ORDER BY m.rank ASC";
        $sql .= " LIMIT 1";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$fk_team, $rowid]);

        return $requete->fetch(PDO::FETCH_OBJ);
    }

    public function fetchPrevRank($rowid, $fk_team)
    {
        $sql = "SELECT m.rank AS prevRank, rowid";
        $sql .= " FROM map_columns AS m";
        $sql .= " WHERE fk_team = ?";
        $sql .= " AND m.rank < (SELECT rank FROM map_columns WHERE rowid = ?)";
        $sql .= " ORDER BY m.rank DESC";
        $sql .= " LIMIT 1";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$fk_team, $rowid]);

        return $requete->fetch(PDO::FETCH_OBJ);
    }


    // CREATE

    public function create(string $name, $fk_team)
    {
        $sql = "SELECT MAX(rank) AS rank";
        $sql .= " FROM map_columns";
        $sql .= " WHERE fk_team = ?";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$fk_team]);
        $obj = $requete->fetch(PDO::FETCH_OBJ);
        $rank = $obj->rank + 1;

        $sql = "INSERT INTO map_columns (name, fk_team, rank)";
        $sql .= " VALUES (?,?,?)";
        
        $requete = $this->getBdd()->prepare($sql);
        return $requete->execute([$name, $fk_team, $rank]);
    }

    // UPDATE

    public function updateName($name, $rowid = null)
    {
        $rowid = $rowid == null ? $this->rowid : $rowid;

        $sql = "UPDATE map_columns";
        $sql .= " SET name = ?";
        $sql .= " WHERE rowid = ?";

        $requete = $this->getBdd()->prepare($sql);
        return $requete->execute([$name, $rowid]);
    }

    public function updatefk_team($fk_team, $rowid = null)
    {
        $rowid = $rowid == null ? $this->id : $rowid;

        $sql = "UPDATE map_columns";
        $sql .= " SET fk_team = ?";
        $sql .= " WHERE rowid = ?";

        $requete = $this->getBdd()->prepare($sql);
        return $requete->execute([$fk_team, $rowid]);
    }

    public function updateRank($rank, $rowid = null)
    {
        $rowid = $rowid == null ? $this->id : $rowid;

        $sql = "UPDATE map_columns";
        $sql .= " SET rank = ?";
        $sql .= " WHERE rowid = ?";

        $requete = $this->getBdd()->prepare($sql);
        return $requete->execute([$rank, $rowid]);
    }


    // DELETE

    public function delete($rowid = null)
    {
        $rowid = $rowid == null ? $this->id : $rowid;

        $sql = "DELETE FROM map_columns";
        $sql .= " WHERE rowid = ?";

        $requete = $this->getBdd()->prepare($sql);
        return $requete->execute([$rowid]);
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
        $status = array();

        $status[] = $rank = $this->fetchRank($rowid);
        if($direction == 'right')
        {
            $obj = $this->fetchNextRank($rowid, $fk_team);
            $otherRank = $obj->nextRank;
            $status[] = $obj;
        }
        else if($direction == 'left')
        {
            $obj = $this->fetchPrevRank($rowid, $fk_team);
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