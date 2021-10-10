<?php 
Class MapColumns extends Modele
{
    private $rowid;
    private $name;
    private $tasks = array();

    public function __construct($rowid = null)
    {
        if($rowid != null)
        {
            $sql = "SELECT m.rowid, m.name, m.fk_team";
            $sql .= " FROM map_columns AS m";
            $sql .= " WHERE m.rowid = ?";

            $requete = $this->getBdd()->prepare($sql);
            $requete->execute([$rowid]);

            $Column = $requete->fetch(PDO::FETCH_OBJ);

            $this->rowid = $Column->rowid;
            $this->name = $Column->name;
            $this->fk_team = $Column->fk_team;

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


    // FETCH

    public function fetch($rowid)
    {
        $sql = "SELECT m.rowid, m.name, m.fk_team";
        $sql .= " FROM map_columns AS m";
        $sql .= " WHERE m.rowid = ?";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$rowid]);

        return $requete->fetch(PDO::FETCH_OBJ);
    }

    public function fetchAll($fk_team)
    {
        $sql = "SELECT m.rowid, m.name, m.fk_team";
        $sql .= " FROM map_columns AS m";
        $sql .= " WHERE m.fk_team = ?";
        $sql .= " ORDER BY rowid ASC";

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


    // CREATE

    public function create(string $name, $fk_team)
    {
        $sql = "INSERT INTO map_columns (name, fk_team)";
        $sql .= " VALUES (? , ?)";
        
        $requete = $this->getBdd()->prepare($sql);
        return $requete->execute([$name, $fk_team]);
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

}

?>