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

    public function inscriptionOrg($email, $password, $name, $consent)
    {
        $sql = "INSERT INTO organizations (email, password, name, consent)";
        $sql .= " VALUES(?, ?, ?, ?)";

        $requete = $this->getBdd()->prepare($sql);
        return $requete->execute([$email, $password, $name, $consent]);
    }
}
?>