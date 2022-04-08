<?php 
Class LogHistory extends Modele
{
    protected $rowid;
    protected $fk_author;
    protected $date_creation;
    protected $status;
    protected $action;
    protected $object_type;
    protected $object_name;
    protected $value;
    protected $identification;
    protected $fk_organization;
    protected $exception;
    protected $platform;
    protected $ipAddress;

    public function __construct($rowid = null)
    {
        if($rowid != null)
        {
            $sql = "SELECT *";
            $sql .= " FROM storieshelper_log_history";
            $sql .= " WHERE rowid = ?";

            $requete = $this->getBdd()->prepare($sql);
            $requete->execute([$rowid]);

            if($requete->rowCount() > 0)
            {
                $obj = $requete->fetch(PDO::FETCH_OBJ);
                
                $this->rowid            = intval($obj->rowid);
                $this->fk_author        = intval($obj->fk_author);
                $this->date_creation    = $obj->date_creation;
                $this->status           = $obj->status;
                $this->action           = $obj->action;
                $this->object_type      = $obj->object_type;
                $this->object_name      = $obj->object_name;
                $this->fk_organization  = intval($obj->fk_organization);
                $this->value            = $obj->value;
                $this->identification   = $obj->identification;
                $this->exception        = $obj->exception;
                $this->platform         = $obj->platform;
                $this->ipAddress        = $obj->ip_address;
            }
        }
    }

    public function initialize($Obj)
    {
        $this->rowid            = intval($Obj->rowid);
        $this->fk_author        = intval($Obj->fk_author);
        $this->date_creation    = $Obj->date_creation;
        $this->action           = $Obj->action;
        $this->object_type      = $Obj->object_type;
        $this->object_name      = $Obj->object_name;
        $this->value            = $Obj->value;
        $this->identification   = $Obj->identification;
        $this->fk_organization  = intval($Obj->fk_organization);
        $this->status           = $Obj->status;
        $this->exception        = $Obj->exception;
        $this->platform         = $Obj->platform;
        $this->ipAddress        = $Obj->ip_address;
    }

    public function setRowid($rowid)
    {
        $this->rowid = $rowid;
    }

    public function setFk_author($fk_author)
    {
        $this->fk_author = $fk_author;
    }

    public function setDate_creation($date_creation)
    {
        $this->date_creation = $date_creation;
    }
    
    public function setAction($action)
    {
        $this->action = $action;
    }

    public function setObject_type($object_type)
    {
        $this->object_type = $object_type;
    }

    public function setObject_name($object_name)
    {
        $this->object_name = $object_name;
    }

    public function setAdmin($admin)
    {
        $this->admin = $admin;
    }

    public function setFk_organization($fk_organization)
    {
        $this->fk_organization = $fk_organization;
    }

    public function getRowid()
    {
        return $this->rowid;
    }

    public function getFk_author()
    {
        return $this->fk_author;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getDate_creation()
    {
        return $this->date_creation;
    }

    public function getAction()
    {
        return $this->action;
    }
    
    public function getObject_type()
    {
        return $this->object_type;
    }
    
    public function getObject_name()
    {
        return $this->object_name;
    }

    public function getAdmin()
    {
        return $this->admin;
    }

    public function getFk_organization()
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
        $requete->execute([$rowid]);

        if($requete->rowCount() > 0)
        {
            $obj = $requete->fetch(PDO::FETCH_OBJ);
    
            $this->rowid            = intval($obj->rowid);
            $this->fk_author        = intval($obj->fk_author);
            $this->date_creation    = $obj->date_creation;
            $this->action           = $obj->action;
            $this->object_type      = $obj->object_type;
            $this->object_name      = $obj->object_name;
            $this->value            = $obj->value;
            $this->identification   = $obj->identification;
            $this->platform         = $obj->platform;
            $this->ipAddress        = $obj->ip_address;
        }
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
    

    public static function create($fk_author, $action, $object, $object_id, $object_parent, $object_parent_id, $fk_organization, $status, $exception = null, $ip_address = '', $page = '')
    {

        $sql = "INSERT INTO storieshelper_log_history (fk_author, date_creation, `action`, `object`, `object_id`, `object_parent`, `object_parent_id`, fk_organization, `status`, exception, ip_address, `page`)";
        $sql .= " VALUES (?,NOW(),?,?,?,?,?,?,?,?,?,?)";

        // development environment
        $PDO = new PDO('mysql:host=localhost;dbname=storieshelper;charset=UTF8', 'USER', '3B1433BDD106CA59551C99703DD8D4B083FAF6560E1C68C8FC57F8AC56E57165');
        $PDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // production environment
        // $PDO = new PDO('mysql:host=ipssisqstorieshe.mysql.db;dbname=ipssisqstorieshe;charset=UTF8', 'ipssisqstorieshe', 'Ipssi2022storieshelper');

        $requete = $PDO->prepare($sql);
        $requete->execute([$fk_author, $action, $object, $object_id, $object_parent, $object_parent_id, $fk_organization, $status, $exception, $ip_address, $page]);
    }

    public function delete()
    {
        $sql = "DELETE FROM storieshelper_log_history";
        $sql .= " WHERE rowid = ?";

        $requete = $this->getBddSafe()->prepare($sql);
        return $requete->execute([$this->rowid]);
    }
}

?>