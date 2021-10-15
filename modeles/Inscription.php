<?php 
class Inscription extends Modele
{
    private $OrgNames = [];
    private $OrgEmails = [];

    public function __construct()
    {
        $sql = "SELECT *";
        $sql .= " FROM organizations";
        
        $requete = $this->getBdd()->prepare($sql);
        $requete->execute();

        $Organizations = $requete->fetchAll(PDO::FETCH_OBJ);

        foreach($Organizations as $Organization)
        {
            $OrgNames[] = $Organization->name;
            $OrgEmails[] = $Organization->email;
        }
    }

    // METHODES
    public function CheckOrgByName($OrgName)
    {
        if(array_search($this->OrgNames, $OrgName))
        {
            return true;
        }
        else 
        {
            return false;
        }
    }

    public function CheckOrgByEmail($emailOrg)
    {
        if(array_search($this->emailsOrg, $emailOrg))
        {
            return true;
        } 
        else 
        {
            return false;
        }
    }

    public function inscriptionOrg($email, $password, $name)
    {
        $status = array();

        $sql = "INSERT INTO organizations (email, password, name)";
        $sql .= " VALUES(?, ?, ?)";

        $requete = $this->getBdd()->prepare($sql);
        $status[] = $requete->execute([$email, $password, $name]);
    
        $sql = "SELECT max(rowid) AS maxId"; 
        $sql .= " FROM organizations";

        $requete = $this->getBdd()->prepare($sql);
        $status[] = $requete->execute();

        $idMax = $requete->fetch(PDO::FETCH_OBJ);
    
        if($idMax->maxId == null)
        {
            $idMax->maxId = 1;
        }

        $idMax->maxId = intval($idMax->maxId);
        
        $sql = "INSERT INTO positions (name, fk_organization, fk_role)";
        $sql .= " VALUES (?, ?, ?);";

        $requete = $this->getBdd()->prepare($sql);
        $status[] = $requete->execute(["indéfini",$idMax->maxId, 4]);

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