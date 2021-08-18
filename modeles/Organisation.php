<?php
class Organisation extends Modele
{
    private $id;
    private $nom;
    private $email;
    private $mdp;
    private $equipes = [];
    private $utilisateurs = [];
    private $postes = [];
    private $clients = [];
    private $projets = [];

    public function __construct($id = null)
    {
        if($id != null)
        {
            $sql = "SELECT * FROM organisations WHERE idOrganisation = ?";
            $requete = $this->getBdd()->prepare($sql);
            $requete->execute([$id]);

            $Organisation = $requete->fetch(PDO::FETCH_ASSOC);
            
            $this->idOrganisation = $id;
            $this->nom = $Organisation["nom"];
            $this->email = $Organisation["email"];

            $Equipe = new Equipe();
            $equipes = $Equipe->fetchAll($this->id);

            foreach($equipes as $equipe)
            {
                $ObjetEquipe = new Equipe($equipe["idEquipe"]);
                $this->equipes[] = $ObjetEquipe; 
            }

            $User = new User();
            $utilisateurs = $User->fetchAll($this->id);

            foreach($utilisateurs as $utilisateur)
            {
                $ObjetUtilisateur = new User($utilisateur['idUtilisateur']);
                $this->utilisateurs[] = $ObjetUtilisateur;
            }

            $Poste = new Poste();
            $postes = $Poste->fetchAll($this->id);
            foreach($postes as $poste)
            {
                $ObjetPoste = new Poste($poste['idPoste']);
                $this->postes[] = $ObjetPoste;
            }

            $Client = new Client();
            $clients = $Client->fetchAll($this->id);
            foreach($clients as $client)
            {
                $ObjetClient = new Client($client['idClient']);
                $this->clients[] = $ObjetClient;
            }

            $Projet = new Projet();
            $projets = $Projet->fetchAll($this->id);
            foreach($projets as $projet)
            {
                $ObjetProjet = new Projet($projet['idProjet']);
                $this->projets[] = $ObjetProjet; 
            }
        }
    }

    // SETTER
    public function setNom($nom)
    {
        $this->nom = $nom;
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
    
    public function getInfosChefsEquipes()
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
            }
            else
            {
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
            }
            else
            {
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
            }
            else
            {
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
            } 
            else 
            {
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
            } 
            else 
            {
                if($maxIdProjet < $idProjet)
                {
                    $maxIdProjet = $idProjet;
                }
            }
        }
        return $maxIdProjet;
    }

    public function CountUsersByEquipes()
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

    public function CountUsersByPoste()
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


    //! UPDATE

    public function updateNom($nom)
    {
        $sql = "UPDATE organisations SET nom = ? WHERE idOrganisation = ?";
        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$nom, $this->idOrganisation]);
    }

    public function updateEmail($email)
    {
        $sql = "UPDATE organisations SET email = ? WHERE idOrganisation = ?";
        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$email, $this->idOrganisation]);
    }

    public function updateMdp($mdp)
    {
        $sql = "UPDATE organisations SET mdp = ? WHERE idOrganisation = ?";
        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$mdp, $this->idOrganisation]);
    }


    //! DELETE 

    public function delete()
    {
        $sql = "DELETE FROM travaille_sur";
        $sql .= "INNER JOIN equipes USING(idEquipe)";
        $sql .= "WHERE idOrganisation = ?";
        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$this->id]);
    
        $sql = "DELETE FROM clients  WHERE idOrganisation = ?";
        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$this->id]);
    
        $sql = "DELETE FROM projets WHERE idOrganisation = ?";
        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$this->id]);
    
        $sql = "DELETE FROM utilisateurs WHERE idOrganisation = ?";
        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$this->id]);
        
        $sql = "DELETE FROM equipes WHERE idOrganisation = ?";
        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$this->id]);
        
        $sql = "DELETE FROM postes WHERE idOrganisation = ?";
        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$this->id]);
        
        $sql = "DELETE FROM organisations WHERE idOrganisation = ?";
        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$this->id]);

        $sql = "DELETE commande_client";
        $sql .= " FROM commande_client";
        $sql .= " INNER JOIN projets USING(idProjet)"; 
        $sql .= " INNER JOIN travaille_sur USING(idProjet)"; 
        $sql .= " INNER JOIN equipes USING(idEquipe)";
        $sql .= " WHERE equipes.idOrganisation = ?";
        $requete = $this->getBdd()->prepare($sql);
    
        session_destroy();
    }
}
?>