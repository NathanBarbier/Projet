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

    public function setPoste($nP,$idP)
    {
        $requete = $this->getBdd()->prepare("UPDATE postes SET nomPoste = ? WHERE idPoste = ?");
        $requete->execute([$nP, $idP]);

        $requete = $this->getBdd()->prepare("SELECT * FROM postes WHERE idPoste = ?");
        $requete->execute([$idP]);
        $Poste = $requete->fetch(PDO::FETCH_ASSOC);

        $this->postes[] = new Poste($Poste['idPoste']);
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

    public function getInfosPosteByIdPoste($idP)
    {
        $infosPoste = [];
        foreach($this->getPostesOrg() as $Poste)
        {
            if($Poste['idPoste'] == $idP)
            {
                $infosPoste[] = [
                    "idPoste" => $Poste["idPoste"],
                    "nomPoste" => $Poste["nomPoste"],
                    "idOrganisation" => $Poste["idOrganisation"],
                    "idRole" => $Poste["idRole"],
                ];
            }
            return $infosPoste;
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

    public function getInfosChefEquipeByIdEquipe($idE)
    {
        $infosChefEquipe = [];
        foreach($this->getEquipesOrg() as $equipe)
        {
            if($equipe->getIdEquipe() == $idE)
            {
                foreach($this->getUsersOrg() as $utilisateur)
                {
                    if($utilisateur->getIdUser == $equipe->getChefEquipe)
                    {
                        $infosChefEquipe[] = [
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
                    return $infosChefEquipe;
                    }
                }
            }
        }
    }
    
    public function recupChefEquipe($idEquipe)
    {
        $requete = $this->getBdd()->prepare("SELECT utilisateurs.nom, utilisateurs.prenom, utilisateurs.email FROM equipes INNER JOIN utilisateurs ON utilisateurs.idUtilisateur = equipes.chefEquipe WHERE equipes.idEquipe = ?");
        $requete->execute([$idEquipe]);
        return $requete->fetch(PDO::FETCH_ASSOC);
    }

    public function getMinMaxIdEquipe()
    {
        $extremes = [];
        foreach($this->getEquipesOrg() as $cle => $Equipe)
        {
            $IdEquipe = $Equipe->getIdEquipe();
            if($cle == 0)
            {
                $extremes["minIdE"] = $IdEquipe;
                $extremes["maxIdE"] = $IdEquipe;
            } else {
                if($IdEquipe > $extremes["maxIdE"])
                {
                    $extremes["maxIdE"] = $IdEquipe;
                }
                if($IdEquipe < $extremes["minIdE"])
                {
                    $extremes["minIdE"] = $IdEquipe;
                }
            }
            
        }
        return $extremes;
    }

    public function getMaxIdUser()
    {
        foreach($this->getUsersOrg() as $cle => $utilisateur)
        {
            $idUser = $utilisateur->getIdUser();
            $maxIdUser = null;
            if ($cle == 0)
            {
                $maxIdUser = $utilisateur->getIdUser();
            } else {
                if($maxIdUser < $idUser)
                {
                    $maxIdUser = $idUser;
                }
            }
        }
        return $maxIdUser;
    }

    public function getMaxIdPoste()
    {
        foreach($this->getPostesOrg() as $cle => $poste)
        {
            $idPoste = $poste->getIdPoste();
            $maxIdPoste = null;
            if($cle == 0)
            {
                $maxIdPoste = $poste->getIdPoste();
            } else {
                if($maxIdPoste < $idPoste)
                {
                    $maxIdPoste = $idPoste;
                }
            }
        }
        return $maxIdPoste;
    }

    public function getMaxIdClient()
    {
        foreach($this->getClientsOrg() as $cle => $client)
        {
            $idClient = $client->getIdClient();
            $maxIdClient = null;
            if($cle == 0)
            {
                $maxIdClient = $client->getIdClient();
            } else {
                if($maxIdClient < $idClient)
                {
                    $maxIdClient = $idClient;
                }
            }
        }
        return $maxIdClient;
    }

    public function getMaxIdProjet()
    {
        foreach($this->getProjetsOrg() as $cle => $projet)
        {
            $idProjet = $projet->getIdProjet();
            $maxIdProjet = null;
            if($cle == 0)
            {
                $maxIdProjet = $projet->getIdProjet();
            } else {
                if($maxIdProjet < $idProjet)
                {
                    $maxIdProjet = $idProjet;
                }
            }
        }
        return $maxIdProjet;
    }

    public function getCountUsersByEquipes()
    {
        $nbUsersParEquipe = [];
        foreach($this->getEquipesOrg() as $Equipe)
        {
            foreach($Equipe->getCountMembres() as $nbUsers)
            {
                $idE = $Equipe->getIdEquipe();
                $nbUsersParEquipe[] = [
                    $idE => $nbUsers,
                ];
            }
        }
        return $nbUsersParEquipe;
    }

    public function getCountUsersByPoste()
    {
        $nbUsersParPoste = [];
        foreach($this->getPostesOrg() as $Poste)
        {
            foreach($this->getUsersOrg() as $utilisateur)
            {
                $users = [];
                if($utilisateur->getIdPosteUser() == $Poste->getIdPoste())
                {
                    $users[] = $utilisateur->getIdUser();
                }
                $nbUsers = count($users);
                $nbUsersParPoste[] = [
                    $Poste->getIdPoste => $nbUsers,
                ];
            }
        }
        return $nbUsersParPoste;
    }

    // RECUP = GETTER UNIQUEMENT DANS CONSTRUCT
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
        $requete = $this->getBdd()->prepare("SELECT idProjet, nom, type, DateDebut, DateRendu, Etat, chefProjet FROM projets INNER JOIN travaille_sur USING(idProjet) INNER JOIN equipes USING(idEquipe) WHERE equipes.idOrganisation = ?");
        $requete->execute([$this->idOrganisation]);
        return $requete->fetchAll(PDO::FETCH_ASSOC);
    }

    // ADD (INSERT BDD + UPDATE PROPRIETES)

    public function addClient($nC)
    {
        $requete = $this->getBdd()->prepare("INSERT INTO clients (nom) VALUES (?)");
        $requete->execute([$nC]);

        $requete = $this->getBdd()->prepare("SELECT * FROM clients WHERE idClient = ?");
        $requete->execute([$this->getMaxIdClient()]);
        $Client = $requete->fetch(PDO::FETCH_ASSOC);

        $this->clients[] = new Client($Client['idClient']);
    }

    public function addProjet($nP, $type, $dD, $dR, $etat, $cP)
    {
        $requete = $this->getBdd()->prepare("INSERT INTO projets (nom,type,DateDebut,DateRendu,Etat,chefProjet) VALUES (?,?,?,?,?,?)");
        $requete->execute([$nP, $type, $dD, $dR, $etat, $cP]);

        $requete = $this->getBdd()->prepare("SELECT * FROM projets WHERE idProjet = ?");
        $requete->execute([$this->getMaxIdProjet()]);
        $Projet = $requete->fetch(PDO::FETCH_ASSOC);

        $this->projets[] = new Projet($Projet['idProjet']);
    }
    
    public function addEquipe($nE)
    {
        $requete = $this->getBdd()->prepare("INSERT INTO equipes (nomEquipe, idOrganisation) VALUES (?,?)");
        $requete->execute([$nE, $this->idOrganisation]);

        $requete = $this->getBdd()->prepare("SELECT * FROM equipes WHERE idEquipe = ?");
        $requete->execute([$this->getMinMaxIdEquipe()["maxIdE"]]);
        $Equipe = $requete->fetch(PDO::FETCH_ASSOC);

        $this->equipes[] = new Equipe($Equipe['idEquipe']);
    }

    public function addPoste($nP,$idR)
    {
        // INSERTION
        $requete = $this->getBdd()->prepare("INSERT INTO postes (nomPoste,idOrganisation,idRole) VALUES (?,?,?)");
        $requete->execute([$nP,$this->getIdOrg(),$idR]);
        // RECUPERATION
        $requete = $this->getBdd()->prepare("SELECT * FROM postes WHERE idPoste = ?");
        $requete->execute([$this->getMaxIdPoste()]);
        $Poste = $requete->fetch(PDO::FETCH_ASSOC);
        // INCREMENTATION PROPRIETE
        $this->postes[] = new Poste($Poste['idPoste']);
    }

    public function addUser($nom,$prenom,$dateNaiss,$idP,$em,$idE,$idO)
    {
        // ON CREE UN MDP TEMPORAIRE A L'UTILISATEUR
        $mdp = $this->generateRandomString(6);
        $mdptemp = $mdp;
        $mdp = password_hash($mdp, PASSWORD_BCRYPT);
                        
        // ON INSERE DANS LA BDD LE NOUVEL UTILISATEUR
        $requete = $this->getBdd()->prepare("INSERT INTO utilisateurs (nom, prenom, dateNaiss, mdp, idPoste, email, idEquipe, idOrganisation)
        VALUES (?,?,?,?,?,?,?,?)");
        $requete->execute([$nom,$prenom,$dateNaiss,$mdp,$idP,$em,$idE,$idO]);

        // ON RECUPERE CET UTILISATEUR DANS LA BDD
        $requete = $this->getBdd()->prepare("SELECT * FROM utilisateurs WHERE idUtilisateur = ?");
        $requete->execute([$this->getMaxIdUser()]);
        $Utilisateur = $requete->fetch(PDO::FETCH_ASSOC);

        // ON AJOUTE UN NOUVEL OBJET UTILISATEUR DANS LA PROPRIETE UTILISATEURS DE L'ORGANISATION
        $this->utilisateurs[] = new Utilisateur($Utilisateur["idUtilisateur"]);
        // ON RETOURNE LE MOT DE PASSE TEMPORAIRE A L'UTILISATEUR
        return $mdptemp;
    }

    // DELETE 

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

    public function delPoste($idP)
    {
        // SELECTION DE L'ID DU POSTE "INDEFINI"
        $requete = $this->getBdd()->prepare("SELECT idPoste FROM postes WHERE idOrganisation = ? LIMIT 1");
        $requete->execute([$this->getIdOrg()]);
        $indefini = $requete->fetch(PDO::FETCH_ASSOC);
        
        // REAFFECTATION AU POSTE "INDEFINI" POUR LES UTILISATEURS AYANT LE POSTE EN SUPPRESSION
        $requete = $this->getBdd()->prepare("UPDATE utilisateurs SET idPoste = ? WHERE idPoste = ?");
        $requete->execute([$indefini["idPoste"], $idP]);

        // SUPPRESSION DU POSTE
        $requete = $this->getBdd()->prepare("DELETE FROM postes WHERE idPoste = ?");
        $requete->execute([$idP]);
    }

}
?>