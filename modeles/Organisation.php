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
            $sql = "SELECT * ";
            $sql .= " FROM organisations"; 
            $sql .= " WHERE idOrganisation = ?";

            $requete = $this->getBdd()->prepare($sql);
            $requete->execute([$id]);

            $Organisation = $requete->fetch(PDO::FETCH_OBJ);
            
            $this->id = $id;
            $this->nom = $Organisation->nom;
            $this->email = $Organisation->email;

            $Equipe = new Equipe();
            $equipes = $Equipe->fetchAll($this->id);

            foreach($equipes as $equipe)
            {
                $ObjetEquipe = new Equipe($equipe->idEquipe);
                $this->equipes[] = $ObjetEquipe; 
            }

            $User = new User();
            $utilisateurs = $User->fetchAll($this->id);

            foreach($utilisateurs as $utilisateur)
            {
                $ObjetUtilisateur = new User($utilisateur->idUtilisateur);
                $this->utilisateurs[] = $ObjetUtilisateur;
            }

            $Poste = new Poste();
            $postes = $Poste->fetchAll($this->id);
            foreach($postes as $poste)
            {
                $ObjetPoste = new Poste($poste->idPoste);
                $this->postes[] = $ObjetPoste;
            }

            $Projet = new Projet();
            $projets = $Projet->fetchAll($this->id);
            foreach($projets as $projet)
            {
                $ObjetProjet = new Projet($projet->idProjet);
                $this->projets[] = $ObjetProjet; 
            }
        }
    }


    //! SETTER

    public function setNom($nom)
    {
        $this->nom = $nom;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function setMdp($mdp)
    {
        $this->mdp = password_hash($mdp, PASSWORD_BCRYPT);
    }


    //! GETTER
    public function getId()
    {
        return $this->idOrganisation;
    }

    public function getNom()
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

    public function getEquipes()
    {
        return $this->equipes;
    }

    public function countEquipes()
    {
        return count($this->equipes);
    }

    public function getUsers()
    {
        return $this->utilisateurs;
    }

    public function countUsers()
    {
        return count($this->utilisateurs);
    }

    public function getPostes()
    {
        return $this->postes;
    }

    public function getClients()
    {
        return $this->clients;
    }

    public function getProjets()
    {
        return $this->projets;
    }

    public function getNomPosteByIdUser($idUser)
    {
        foreach($this->getUsers() as $utilisateur)
        {
            if($utilisateur->getIdUser() == $idUser)
            {
                $idPosteUser = $utilisateur->getIdPosteUser();
            }
        }

        foreach($this->getPostes() as $poste)
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
        foreach($this->getPostes() as $Poste)
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
        foreach($this->getUsers() as $utilisateur)
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
        foreach($this->getUsers() as $utilisateur)
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
                "equipe" => $this->getEquipes()[$utilisateur->getIdEquipeUser()]->getNomEquipe(),
                "poste" => $this->getPostes()[$utilisateur->getIdPosteUser()]->getNomPoste(),
            ];
        }
        return $infosUsers;
    }

    public function getUsersByEquipeOrg($idEquipe)
    {
        $usersEquipe = [];
        foreach($this->getEquipes() as $equipe)
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
        foreach($this->getPostes() as $poste)
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
        foreach($this->getEquipes() as $Equipe)
        {
            if($Equipe->getChef() != null)
            {
                $chefsEquipes[] = [
                    "nom" => $this->getUsers()[$Equipe->getChef()]->getNom(),
                    "prenom" => $this->getUsers()[$Equipe->getChef()]->getPrenom(),
                    "email" => $this->getUsers()[$Equipe->getChef()]->getEmail(),
                    "idUser" => $this->getUsers()[$Equipe->getChef()]->getId(),  
                ];
            }
            else
            {
                $chefsEquipes[] = [
                    "nom" => "",
                    "prenom" => "",
                    "email" => "",
                    "idUser" => "",  
                ];
            }
        }
        return $chefsEquipes;
    }

    public function getInfosChefEquipeByIdEquipe($idE)
    {
        $infosChefEquipe = [];
        foreach($this->getEquipes() as $equipe)
        {
            if($equipe->getIdEquipe() == $idE)
            {
                foreach($this->getUsers() as $utilisateur)
                {
                    if($utilisateur->getId() == $equipe->getChefEquipe)
                    {
                        $infosChefEquipe[] = [
                            "idUtilisateur" => $utilisateur->getId(),
                            "nom" => $utilisateur->getNom(),
                            "prenom" => $utilisateur->getPrenom(),
                            "dateNaiss" => $utilisateur->getDateNaiss(),
                            "mdp" => $utilisateur->getMdp(),
                            "idPoste" => $utilisateur->getIdPoste(),
                            "email" => $utilisateur->getEmail(),
                            "idEquipe" => $utilisateur->getIdEquipe(),
                            "idOrganisation" => $utilisateur->getIdOrganisation(),
                        ];
                        return $infosChefEquipe;
                    }
                }
            }
        }
    }

    public function getMinMaxIdEquipe()
    {
        $extremes = new stdClass;
        foreach($this->getEquipes() as $cle => $Equipe)
        {
            $IdEquipe = $Equipe->getId();
            if($cle == 0)
            {
                $extremes->minIdE = $IdEquipe;
                $extremes->maxIdE = $IdEquipe;
            }
            else
            {
                if($IdEquipe > $extremes->maxIdE)
                {
                    $extremes->maxIdE = $IdEquipe;
                }
                if($IdEquipe < $extremes->minIdE)
                {
                    $extremes->minIdE = $IdEquipe;
                }
            }
            
        }
        return $extremes;
    }

    public function getMaxIdUser()
    {
        foreach($this->getUsers() as $cle => $utilisateur)
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
        foreach($this->getPostes() as $cle => $poste)
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
        foreach($this->getClients() as $cle => $client)
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
        foreach($this->getProjets() as $cle => $projet)
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
        foreach($this->getEquipes() as $Equipe)
        {
            $nbUsers = $Equipe->countMembres();
                
            $idE = $Equipe->getId();
            $nbUsersParEquipe[$idE] = $nbUsers;
        }
        return $nbUsersParEquipe;
    }

    public function CountUsersByPoste()
    {
        $nbUsersParPoste = [];
        foreach($this->getPostes() as $Poste)
        {
            foreach($this->getUsers() as $utilisateur)
            {
                $users = [];
                if($utilisateur->getIdPoste() == $Poste->getId())
                {
                    $users[] = $utilisateur->getId();
                }
                $nbUsers = count($users);
                $nbUsersParPoste[$Poste->getId()] = $nbUsers;
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


    //! FETCH

    public function fetchByEmail($email)
    {
        $sql = "SELECT * FROM organisations WHERE email = ?";
        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$email]);

        return $requete->fetch(PDO::FETCH_ASSOC);
    }


    //! METHODES

    public function verifNom($nom)
    {
        $sql = "SELECT * FROM organisations WHERE nom = ?";
        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$nom]);

        if($requete->rowCount() > 0)
        {
            return true;
        }
        else
        {
            return false;
        }

    }

    public function verifEmail($email)
    {
        $sql = "SELECT * ";
        $sql .= " FROM organisations";
        $sql .= " WHERE email = ?";
        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$email]);

        if($requete->rowCount() > 0)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
}
?>