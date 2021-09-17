<?php 
class Inscription extends Modele
{
    private $nomsOrg = [];
    private $emailsOrg = [];

    public function __construct()
    {
        $sql = "SELECT * FROM organisations";
        $requete = $this->getBdd()->prepare($sql);
        $requete->execute();

        $Organisations = $requete->fetchAll(PDO::FETCH_ASSOC);

        foreach($Organisations as $Organisation)
        {
            $nomsOrg[] = $Organisation["nom"];
            $emailsOrg[] = $Organisation["email"];
        }
    }

    // METHODES
    public function verifNomOrg($nomOrganisation)
    {
        if(array_search($this->nomsOrg, $nomOrganisation))
        {
            return true;
        }
        else 
        {
            return false;
        }
    }

    public function verifEmailOrg($emailOrg)
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

    public function inscriptionOrg($mail, $mdp, $nom)
    {
        $status = array();

        $sql = "INSERT INTO organisations (Email, Mdp, Nom) VALUES(?, ?, ?)";
        $requete = $this->getBdd()->prepare($sql);
        $status[] = $requete->execute([$mail, $mdp, $nom]);
    
        $sql = "SELECT max(idOrganisation) AS maxId FROM organisations";
        $requete = $this->getBdd()->prepare($sql);
        $status[] = $requete->execute();

        $idMax = $requete->fetch(PDO::FETCH_ASSOC);
    
        if(empty($idMax["maxId"]))
        {
            $idMax["maxId"] = 1;
        }
        
        $sql = "INSERT INTO equipes (nomEquipe, idOrganisation)";
        $sql = " VALUES (?, ?)";
        $sql = " INSERT INTO postes (nomPoste, idOrganisation)";
        $sql = " VALUES (?, ?)";
        
        $requete = $this->getBdd()->prepare($sql);
        $status[] = $requete->execute(["indéfini",$idMax["maxId"],"indéfini",$idMax["maxId"]]);

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