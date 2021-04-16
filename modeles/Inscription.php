<?php 
class Inscription extends Modele
{
    private $nomsOrg = [];
    private $emailsOrg = [];

    public function __construct()
    {
        $requete = $this->getBdd()->prepare("SELECT * FROM organisations");
        $requete->execute();

        $Organisations = $requete->fetchAll(PDO::FETCH_ASSOC);

        foreach($Organisations as $Organisation)
        {
            $nomsOrg[] = $Organisation["nom"];
            $emailsOrg[] = $Organisation["email"];
        }
    }

    // METHODES
    public function verifNomOrg($nO)
    {
        if(array_search($this->nomsOrg, $nO))
        {
            return true;
        } else {
            return false;
        }
    }

    public function verifEmailOrg($emO)
    {
        if(array_search($this->emailsOrg, $emO))
        {
            return true;
        } else {
            return false;
        }
    }

    public function inscriptionOrg($mail, $mdp, $organisation)
    {
        $requete = $this->getBdd()->prepare("INSERT INTO organisations(Email, Mdp, Nom)
        VALUES(?, ?, ?)");
        $requete->execute([$mail, $mdp, $organisation]);
    
        $requete = $this->getBdd()->prepare("SELECT max(idOrganisation) AS maxId FROM organisations");
        $requete->execute();
        $idMax = $requete->fetch(PDO::FETCH_ASSOC);
    
        if(empty($idMax["maxId"]))
        {
            $idMax["maxId"] = 1;
        }
        
        $requete = $this->getBdd()->prepare("INSERT INTO equipes (nomEquipe, idOrganisation) VALUES (?, ?);
        INSERT INTO postes (nomPoste, idOrganisation) VALUES (?, ?)");
        $requete->execute(["indéfini",$idMax["maxId"],"indéfini",$idMax["maxId"]]);
    }
}
?>