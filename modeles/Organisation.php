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

            $equipes = $this->recupEquipesOrg();
            foreach($equipes as $equipe)
            {
                $ObjetEquipe = new Equipe($equipe["idEquipe"]);
                $this->equipes[] = $ObjetEquipe; 
            }

            $utilisateurs = $this->recupUsersOrg();
            foreach($utilisateurs as $utilisateur)
            {
                $ObjetUtilisateur = new Utilisateur($utilisateur['idUtilisateur']);
                $this->utilisateurs[] = $ObjetUtilisateur;
            }

            $postes = $this->recupPostesOrg();
            foreach($postes as $poste)
            {
                $ObjetPoste = new Poste($poste['idPoste']);
                $this->postes[] = $ObjetPoste;
            }

            $clients = $this->recupClientsOrg();
            foreach($clients as $client)
            {
                $ObjetClient = new Client($client['idClient']);
                $this->clients[] = $ObjetClient;
            }

            $projets = $this->recupProjetsOrg();
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
        $requete = $this->getBdd()->prepare("UPDATE organisations SET nom = ? WHERE idOrganisation = ?");
        $requete->execute([$this->nom, $this->idOrganisation]);
    }

    public function setEmailOrg($eO)
    {
        $this->email = $eO;
        $requete = $this->getBdd()->prepare("UPDATE organisations SET email = ? WHERE idOrganisation = ?");
        $requete->execute([$this->email, $this->idOrganisation]);
    }

    public function setMdpOrg($mdp)
    {
        $this->mdp = password_hash($mdp, PASSWORD_BCRYPT);
        $requete = $this->getBdd()->prepare("UPDATE organisations SET mdp = ? WHERE idOrganisation = ?");
        $requete->execute([$this->mdp, $this->idOrganisation]);
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

    public function getNomPosteByIdUser($idUser)
    {
        foreach($this->getUsersOrg() as $utilisateur)
        {
            if($utilisateur->getIdUser() == $idUser)
            {
                $idPosteUser = $utilisateur->getIdPosteUser();
            }
        }

        foreach($this->getPostesOrg() as $poste)
        {
            if($poste->getIdPoste() == $idPosteUser)
            {
                return $poste->getNomPoste();
            }
        }
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

    public function getInfosUsersOrg()
    {
        $infosUsers = [];
        foreach($this->getUsersOrg() as $utilisateur)
        {
            $infosUsers[] = [
                "idUtilisateur" => $utilisateur->getIdUser(),
                "nom" => $utilisateur->getNomUser(),
                "prenom" => $utilisateur->getPrenomUser(),
                "dateNaiss" => $utilisateur->getDateNaissUser(),
                "mdp" => $utilisateur->getMdpUser(),
                "idPoste" => $utilisateur->getIdPosteUser(),
                "email" => $utilisateur->getEmailUser(),
                "idEquipe" => $utilisateur->getIdEquipeUser(),
                "idOrganisation" => $utilisateur->getIdOrganisationUser(),
                "equipe" => $this->getEquipesOrg()[$utilisateur->getIdEquipeUser()]->getNomEquipe(),
                "poste" => $this->getPostesOrg()[$utilisateur->getIdPosteUser()]->getNomPoste(),
            ];
        }
        return $infosUsers;
    }

    public function getUsersByEquipeOrg($idEquipe)
    {
        $usersEquipe = [];
        foreach($this->getEquipesOrg() as $equipe)
        {
            if($equipe->getIdEquipe() == $idEquipe)
            {
                foreach($equipe->getMembresEquipe() as $utilisateur)
                {
                    $usersEquipe[] = [
                        "idUtilisateur" => $utilisateur->getIdUser(),
                        "nom" => $utilisateur->getNomUser(),
                        "prenom" => $utilisateur->getPrenomUser(),
                        "dateNaiss" => $utilisateur->getDateNaissUser(),
                        "mdp" => $utilisateur->getMdpUser(),
                        "idPoste" => $utilisateur->getIdPosteUser(),
                        "email" => $utilisateur->getEmailUser(),
                        "idEquipe" => $utilisateur->getIdEquipeUser(),
                        "idOrganisation" => $utilisateur->getIdOrganisationUser(),
                    ];
                }
                return $usersEquipe;
            }
        }
    }

    public function getUsersByPoste($idPoste)
    {
        $usersPoste = [];
        foreach($this->getPostesOrg() as $poste)
        {
            if($poste->getIdEquipe() == $idPoste)
            {
                foreach($poste->getMembresPoste() as $utilisateur)
                {
                    $usersEquipe[] = [
                        "idUtilisateur" => $utilisateur->getIdUser(),
                        "nom" => $utilisateur->getNomUser(),
                        "prenom" => $utilisateur->getPrenomUser(),
                        "dateNaiss" => $utilisateur->getDateNaissUser(),
                        "mdp" => $utilisateur->getMdpUser(),
                        "idPoste" => $utilisateur->getIdPosteUser(),
                        "email" => $utilisateur->getEmailUser(),
                        "idEquipe" => $utilisateur->getIdEquipeUser(),
                        "idOrganisation" => $utilisateur->getIdOrganisationUser(),
                    ];
                }
                return $usersPoste;
            }
        }
    }
    
    public function getInfosChefsEquipesOrg()
    {
        $chefsEquipes = [];
        foreach($this->getEquipesOrg() as $equipe)
        {
            $chefsEquipes[] = [
                "nom" => $this->getUsersOrg()[$equipe->getChefEquipe()]->getNomUser(),
                "prenom" => $this->getUsersOrg()[$equipe->getChefEquipe()]->getPrenomUser(),
                "email" => $this->getUsersOrg()[$equipe->getChefEquipe()]->getEmailUser(),
                "idUser" => $this->getUsersOrg()[$equipe->getChefEquipe()]->getIdUser(),  
            ];
        }
        return $chefsEquipes;
    }

    public function getMinMaxIdEquipe()
    {
        $extrIdEquipe = [];
        foreach($this->getEquipesOrg() as $cle => $Equipe)
        {
            $IdEquipe = $Equipe->getIdEquipe();
            if($cle == 0 )
            {
                $tableau["minIdE"] = $IdEquipe;
                $tableau["maxIdE"] = $IdEquipe;
            } else {
                if($IdEquipe > $extrIdEquipe["maxIdE"])
                {
                    $tableau["maxIdE"] = $IdEquipe;
                }
                if($IdEquipe < $extrIdEquipe["minIdE"])
                {
                    $extrIdEquipe["minIdE"] = $IdEquipe;
                }
            }
        }
        return $tableau;
    }

    public function getNbUsersEquipes()
    {
        $nbMembresParEquipe = [];
        foreach($this->getEquipesOrg() as $Equipe)
        {
            foreach($Equipe->getCountMembres() as $nbMembre)
            {
                $idE = $Equipe->getIdEquipe();
                $nbMembresParEquipe[] = [
                    $idE => $nbMembre
                ];
            }
        }
        return $nbMembresParEquipe;
    }

    // METHODES
    // RECUP = METHODES CONSTRUCT
    public function recupClientsOrg()
    {
        $requete = $this->getBdd()->prepare("SELECT clients.nom as nom, clients.idClient as idClient FROM clients INNER JOIN commande_client USING(idClient) INNER JOIN projets USING(idProjet) INNER JOIN travaille_sur USING(idProjet) INNER JOIN equipes USING(idEquipe) WHERE equipes.idOrganisation = ?");
        $requete->execute([$this->idOrganisation]);
        return $requete->fetchAll(PDO::FETCH_ASSOC);
    }

    public function recupUsersOrg()
    {
        $requete = $this->getBdd()->prepare("SELECT * FROM utilisateurs WHERE idOrganisation = ?");
        $requete->execute([$this->idOrganisation]);
        return $requete->fetchAll(PDO::FETCH_ASSOC);
    }

    public function recupEquipesOrg()
    {
        $requete = $this->getBdd()->prepare("SELECT * FROM equipes WHERE idOrganisation = ?");
        $requete->execute([$this->idOrganisation]);
        return $requete->fetchAll(PDO::FETCH_ASSOC);
    }

    public function recupPostesOrg()
    {
        $requete = $this->getBdd()->prepare("SELECT * FROM Postes WHERE idOrganisation = ?");
        $requete->execute([$this->idOrganisation]);
        return $requete->fetchAll(PDO::FETCH_ASSOC);
    }

    public function recupProjetsOrg()
    {
        $requete = $this->getBdd()->prepare("SELECT idProjet, nom, type, DateDebut, DateRendu, Etat, chefProjet FROM projets USING(idProjet) INNER JOIN travaille_sur USING(idProjet) INNER JOIN equipes USING(idEquipe) WHERE equipes.idOrganisation = ?");
        $requete->execute([$this->idOrganisation]);
        return $requete->fetchAll(PDO::FETCH_ASSOC);
    }

    // ADD
    
    public function addEquipe($nE)
    {
        $requete = $this->getBdd()->prepare("INSERT INTO equipes (nomEquipe, idOrganisation) VALUES (?,?)");
        $requete->execute([$nE, $this->idOrganisation]);
    }

    // DELETE ORG

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
}
?>