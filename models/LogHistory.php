<?php 
Class LogHistory extends Modele
{
    private $rowid;
    private $fk_author;
    private $date_creation;
    private $action;
    private $object_type;
    private $object_name;

    public function __construct($rowid = null)
    {
        if($rowid != null)
        {
            $sql = "SELECT rowid, fk_author, date_creation, action, object_type, object_name, admin, fk_organization";
            $sql .= " FROM storieshelper_log_history";
            $sql .= " WHERE rowid = ?";

            $requete = $this->getBdd()->prepare($sql);
            $status = $requete->execute([$rowid]);

            $obj = $requete->fetch(PDO::FETCH_OBJ);

            $this->rowid = $obj->rowid;
            $this->fk_author = $obj->fk_author;
            $this->date_creation = $obj->date_creation;
            $this->action = $obj->action;
            $this->object_type = $obj->object_type;
            $this->object_name = $obj->object_name;
            $this->admin = $obj->admin;
            $this->fk_organization = $obj->fk_organization;
        }
    }

    public function setRowid($rowid)
    {
        $this->rowid = $rowid;
    }

    public function setfk_author($fk_author)
    {
        $this->fk_author = $fk_author;
    }

    public function setdate_creation($date_creation)
    {
        $this->date_creation = $date_creation;
    }
    
    public function setaction($action)
    {
        $this->action = $action;
    }

    public function setobject_type($object_type)
    {
        $this->object_type = $object_type;
    }

    public function setobject_name($object_name)
    {
        $this->object_name = $object_name;
    }

    public function setadmin($admin)
    {
        $this->admin = $admin;
    }

    public function setfk_organization($fk_organization)
    {
        $this->fk_organization = $fk_organization;
    }

    public function getRowid()
    {
        return $this->rowid;
    }

    public function getfk_author()
    {
        return $this->fk_author;
    }

    public function getdate_creation()
    {
        return $this->date_creation;
    }

    public function getaction()
    {
        return $this->action;
    }
    
    public function getobject_type()
    {
        return $this->object_type;
    }
    
    public function getobject_name()
    {
        return $this->object_name;
    }

    public function getadmin()
    {
        return $this->admin;
    }

    public function getfk_organization()
    {
        return $this->fk_organization;
    }


    // FETCH

    public function fetch($rowid = null)
    {
        $rowid = $rowid == null ? $this->rowid : $rowid;

        $sql = "SELECT rowid, fk_author, date_creation, action, object_type, object_name, admin, fk_organisation";
        $sql .= " FROM storieshelper_log_history";
        $sql .= " WHERE rowid = ?";

        $requete = $this->getBdd()->prepare($sql);
        $status = $requete->execute([$rowid]);

        $obj = $requete->fetch(PDO::FETCH_OBJ);

        $this->rowid = $obj->rowid;
        $this->fk_author = $obj->fk_author;
        $this->date_creation = $obj->date_creation;
        $this->action = $obj->action;
        $this->object_type = $obj->object_type;
        $this->object_name = $obj->object_name;
        $this->admin = $obj->admin;
    }

    public function fetchAll($fk_organization = null)
    {
        $fk_organization = $fk_organization == null ? $this->fk_organization : $fk_organization;

        $sql = "SELECT rowid, fk_author, date_creation, action, object_type, object_name, admin, fk_organisation";
        $sql .= " FROM storieshelper_log_history";
        $sql .= " WHERE fk_organization = ?";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$fk_organization]);

        return $requete->fetchAll(PDO::FETCH_OBJ);
    }
    

    public static function create($fk_author, $action, $object_type, $object_name, $value = null)
    {
        $sql = "INSERT INTO storieshelper_log_history (fk_author, date_creation, action, object_type, object_name, value)";
        $sql .= " VALUES (?, NOW(),?,?,?,?)";

        // development environment
        $PDO = new PDO('mysql:host=localhost;dbname=storieshelper;charset=UTF8', 'root');
        // production environment
        // $PDO = new PDO('mysql:host=ipssisqstorieshe.mysql.db;dbname=ipssisqstorieshe;charset=UTF8', 'ipssisqstorieshe', 'Ipssi2022storieshelper');
        
        $requete = $PDO->prepare($sql);
        $requete->execute([$fk_author, $action, $object_type, $object_name, $value]);
    }

    public function delete($rowid)
    {
        $sql = "DELETE FROM storieshelper_log_history";
        $sql .= " WHERE rowid = ?";

        $requete = $this->getBdd()->prepare($sql);
        return $requete->execute([$rowid]);
    }
}

?>