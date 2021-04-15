<?php
class Organisation extends Modele
{
    private $idOrganisation;
    private $nom;
    private $email;
    private $mdp;
    private $equipes = [];
    private $utilisateurs = [];
    private $postes = [];
    private $clients = [];
    private $projets = [];

    public function __construct($idO = null)
    {
        if($idO != null)
        {
            $requete = $this->getBdd()->prepare("SELECT * FROM organisations WHERE idOrganisation = ?");
            $requete->execute([$idO]);
            $Organisation = $requete->fetch(PDO::FETCH_ASSOC);
            
            $this->idOrganisation = $idO;
            $this->nom = $Organisation["nom"];
            $this->email = $Organisation["email"];

            $equipes = $this->recupEquipes($idO);
            foreach($equipes as $equipe)
            {
                $ObjetEquipe = new Equipe($equipe["idEquipe"]);
                $this->equipes[] = $ObjetEquipe; 
            }

            $utilisateurs = $this->recupInfosUtilisateurs($idO);
            foreach($utilisateurs as $utilisateur)
            {
                $ObjetUtilisateur = new Utilisateur($utilisateur['idUtilisateur']);
                $this->utilisateurs[] = $ObjetUtilisateur;
            }

            $postes = $this->recupPostes($idO);
            foreach($postes as $poste)
            {
                $ObjetPoste = new Poste($poste['idPoste']);
                $this->postes[] = $ObjetPoste;
            }

            $clients = $this->recupClients($idO);
            foreach($clients as $client)
            {
                $ObjetClient = new Client($idO);
                $this->clients[] = $ObjetClient;
            }

            $projets = $this->recupProjets($idO);
            foreach($projets as $projet)
            {
                $ObjetProjet = new Projet($idO);
                $this->projets[] = $ObjetProjet; 
            }
        }
    }

    // SETTER
    public function setNomOrganisation($nO)
    {
        $this->nom = $nO;
    }

    public function setEmailOrganisation($eO)
    {
        $this->email = $eO;
    }

    // GETTER
    public function getIdOrganisation()
    {
        return $this->idOrganisation;
    }

    public function getNomOrganisation()
    {
        return $this->nom;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getMdp()
    {
        return $this->mdp;
    }

    public function recupererEquipes($idOrganisation)
    {
        $requete = $this->getBdd()->prepare("SELECT * FROM equipes WHERE idOrganisation = ?");
        $requete->execute([$idOrganisation]);
        return $requete->fetchAll(PDO::FETCH_ASSOC);
    }

    public function supprimerOrganisation()
    {
    
        $requete = $this->getBdd()->prepare("DELETE travaille_sur FROM travaille_sur INNER JOIN equipes USING(idEquipe) WHERE idOrganisation = ?");
        $requete->execute([$_SESSION["idOrganisation"]]);
    
        $requete = $this->getBdd()->prepare("DELETE FROM clients  WHERE idOrganisation = ?");
        $requete->execute([$_SESSION["idOrganisation"]]);
    
        $requete = $this->getBdd()->prepare("DELETE FROM projets WHERE idOrganisation = ?");
        $requete->execute([$_SESSION["idOrganisation"]]);
    
        $requete = $this->getBdd()->prepare("DELETE FROM utilisateurs WHERE idOrganisation = ?");
        $requete->execute([$_SESSION["idOrganisation"]]);
        
        $requete = $this->getBdd()->prepare("DELETE FROM equipes WHERE idOrganisation = ?");
        $requete->execute([$_SESSION["idOrganisation"]]);
        
        $requete = $this->getBdd()->prepare("DELETE FROM postes WHERE idOrganisation = ?");
        $requete->execute([$_SESSION["idOrganisation"]]);
        
        $requete = $this->getBdd()->prepare("DELETE FROM organisations WHERE idOrganisation = ?");
        $requete->execute([$_SESSION["idOrganisation"]]);
    
        // supprimer aussi les informations en rapport avec l'organisation
        session_destroy();
    
    }
    
    public function recupInfosOrganisation($idOrganisation)
    {
        // Récuperer Nom organisation / email / nombre d'employés / nombre d'équipes
        $requete = $this->getBdd()->prepare("SELECT organisations.nom AS nom, organisations.email AS email, count(equipes.idEquipe) AS nombreEquipes, count(utilisateurs.idUtilisateur) AS nombreEmployes FROM organisations INNER JOIN utilisateurs USING(idOrganisation) INNER JOIN equipes USING(idOrganisation) WHERE organisations.idOrganisation = ? ");
        $requete->execute([$idOrganisation]);
        return $requete->fetch(PDO::FETCH_ASSOC);
    }
    
    public function recupNomOrganisation($organisation)
    {
        $requete = $this->getBdd()->prepare("SELECT Nom FROM organisations WHERE Nom = ?");
        $requete->execute([$organisation]);
        if($requete->rowCount() > 0)
        {
            return true;
        } else {
            return false;
        }
    }
    
    public function recupEmailOrganisation($mail)
    {
        $requete = $this->getBdd()->prepare("SELECT Email FROM organisations WHERE Email = ?");
        $requete->execute([$mail]);
        if($requete->rowCount() > 0)
        {
            return true;
        } else {
            return false;
        }
    }
    
    public function creerOrganisation($mail, $mdp, $organisation)
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
    
    public function recupOrganisationMail($mail)
    {
        $requete = $this->getBdd()->prepare("SELECT * FROM organisations WHERE email = ?");
        $requete->execute([$mail]);
        return $requete->fetch(PDO::FETCH_ASSOC);
    }
    
    public function verifEmailOrganisation($email)
    {
        $requete = $this->getBdd()->prepare("SELECT email FROM organisations WHERE email = ?");
        $requete->execute([$email]);
    
        if($requete->rowcount() > 0)
        {
            return true;
        } else {
            return false;
        }
    }
}
?>