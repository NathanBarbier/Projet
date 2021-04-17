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

            $equipes = $this->recupEquipesOrg($idO);
            foreach($equipes as $equipe)
            {
                $ObjetEquipe = new Equipe($equipe["idEquipe"]);
                $this->equipes[] = $ObjetEquipe; 
            }

            $utilisateurs = $this->recupUsersOrg($idO);
            foreach($utilisateurs as $utilisateur)
            {
                $ObjetUtilisateur = new Utilisateur($utilisateur['idUtilisateur']);
                $this->utilisateurs[] = $ObjetUtilisateur;
            }

            $postes = $this->recupPostesOrg($idO);
            foreach($postes as $poste)
            {
                $ObjetPoste = new Poste($poste['idPoste']);
                $this->postes[] = $ObjetPoste;
            }

            $clients = $this->recupClientsOrg($idO);
            foreach($clients as $client)
            {
                $ObjetClient = new Client($client['idClient']);
                $this->clients[] = $ObjetClient;
            }

            $projets = $this->recupProjetsOrg($idO);
            foreach($projets as $projet)
            {
                $ObjetProjet = new Projet($projet['idProjet']);
                $this->projets[] = $ObjetProjet; 
            }
        }
    }

    // SETTER
    public function setNomOrg($nO)
    {
        $this->nom = $nO;
    }

    public function setEmailOrg($eO)
    {
        $this->email = $eO;
    }

    public function setMdpOrg($mdp)
    {
        $this->mdp = password_hash($mdp, PASSWORD_BCRYPT);
    }

    // GETTER
    public function getIdOrg()
    {
        return $this->idOrganisation;
    }

    public function getNomOrg()
    {
        return $this->nom;
    }

    public function getEmailOrg()
    {
        return $this->email;
    }

    public function getMdpOrg()
    {
        return $this->mdp;
    }

    public function getEquipesOrg()
    {
        return $this->equipes;
    }

    public function getCountEquipesOrg()
    {
        return count($this->equipes);
    }

    public function getUsersOrg()
    {
        return $this->utilisateurs;
    }

    public function getcountUsersOrg()
    {
        return count($this->utilisateurs);
    }

    public function getPostesOrg()
    {
        return $this->postes;
    }

    public function getClientsOrg()
    {
        return $this->clients;
    }

    public function getProjetsOrg()
    {
        return $this->projets;
    }

    // METHODES

    public function recupEquipesOrg($idO)
    {
        $requete = $this->getBdd()->prepare("SELECT * FROM equipes WHERE idOrganisation = ?");
        $requete->execute([$idO]);
        return $requete->fetchAll(PDO::FETCH_ASSOC);
    }

    public function recupClientsOrg($idO)
    {
        $requete = $this->getBdd()->prepare("SELECT clients.nom as nom, clients.idClient as idClient FROM clients INNER JOIN commande_client USING(idClient) INNER JOIN projets USING(idProjet) INNER JOIN travaille_sur USING(idProjet) INNER JOIN equipes USING(idEquipe) WHERE equipes.idOrganisation = ?");
        $requete->execute([$idO]);
        return $requete->fetchAll(PDO::FETCH_ASSOC);
    }

    public function recupUsersOrg($idO)
    {
        $requete = $this->getBdd()->prepare("SELECT * FROM Utilisateurs WHERE idOrganisation = ?");
        $requete->execute([$idO]);
        return $requete->fetchAll(PDO::FETCH_ASSOC);
    }

    public function recupPostesOrg($idO)
    {
        $requete = $this->getBdd()->prepare("SELECT * FROM Postes WHERE idOrganisation = ?");
        $requete->execute([$idO]);
        return $requete->fetchAll(PDO::FETCH_ASSOC);
    }

    public function recupProjetsOrg($idO)
    {
        $requete = $this->getBdd()->prepare("SELECT idProjet, nom, type, DateDebut, DateRendu, Etat, chefProjet FROM projets USING(idProjet) INNER JOIN travaille_sur USING(idProjet) INNER JOIN equipes USING(idEquipe) WHERE equipes.idOrganisation = ?");
        $requete->execute([$idO]);
        return $requete->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getMinMaxIdEquipe()
    {
        $tableau = [];
        foreach($this->getEquipesOrg() as $cle => $Equipe)
        {
            
            $IdEquipe = $Equipe->getIdEquipe();

            if($cle == 0 )
            {
                $tableau["minIdE"] = $IdEquipe;
                $tableau["maxIdE"] = $IdEquipe;
            } else {
                if($IdEquipe > $tableau["maxIdE"])
                {
                    $tableau["maxIdE"] = $IdEquipe;
                }
                if($IdEquipe < $tableau["minIdE"])
                {
                    $tableau["minIdE"] = $IdEquipe;
                }
            }
            
        }
        return $tableau;
    }

    public function recupMaxMinIdEquipes($idO)
    {
        $requete = $this->getBdd()->prepare("SELECT max(idEquipe) as MaxId, min(idEquipe) as MinId FROM equipes WHERE idOrganisation = ?");
        $requete->execute([$idO]);
        return $requete->fetch(PDO::FETCH_ASSOC);
    }
    
    public function recupererNombreMembreParEquipe($idOrganisation)
    {
        $requete = $this->getBdd()->prepare("SELECT idEquipe, count(utilisateurs.idEquipe) as UtilisateursParEquipe FROM equipes left join utilisateurs using(idEquipe) where equipes.idOrganisation = ? group by equipes.idEquipe");
        $requete->execute([$idOrganisation]);
        return $requete->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function ajouterEquipe($ajoutEquipe, $idOrganisation)
    {
        $requete = $this->getBdd()->prepare("INSERT INTO equipes (nomEquipe, idOrganisation) VALUES (?,?)");
        $requete->execute([$ajoutEquipe, $idOrganisation]);
    }

    public function recupChefEquipesParOrganisation($idOrganisation)
    {
        $requete = $this->getBdd()->prepare("SELECT utilisateurs.nom, utilisateurs.prenom, utilisateurs.email, chefEquipe, utilisateurs.idUtilisateur FROM equipes LEFT JOIN utilisateurs ON utilisateurs.idUtilisateur = equipes.chefEquipe WHERE equipes.idOrganisation = ?");
        $requete->execute([$idOrganisation]);
        return $requete->fetchAll(PDO::FETCH_ASSOC);
    }

    public function delOrg()
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

        $requete = $this->getBdd()->prepare("DELETE commande_client FROM commande_client INNER JOIN projets USING(idProjet) INNER JOIN travaille_sur USING(idProjet) INNER JOIN equipes USING(idEquipe) WHERE equipes.idOrganisation = ?");
    
        // supprimer aussi les informations en rapport avec l'organisation
        session_destroy();
    }

    public function getIdByNomPrenom($nom, $prenom)
    {
        foreach($this->getUsersOrg() as $utilisateur)
        {
            if($utilisateur->getNomUser() == $nom && $utilisateur->getPrenomUser() == $prenom)
            {
                return $utilisateur->getIdUser();
            }
        }
    }
}


?>