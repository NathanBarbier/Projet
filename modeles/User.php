<?php
class User extends Modele
{
    protected $id;
    protected $firstname;
    protected $lastname;
    protected $birth;
    protected $password;
    protected $email;
    protected $idPoste;
    protected $idEquipe;
    protected $idOrganisation;

    public function __construct($id = null)
    {
        if($id != null)
        {
            $sql = "SELECT * FROM utilisateurs WHERE idUtilisateur = ?";
            $requete = $this->getBdd()->prepare($sql);
            $requete->execute([$id]);

            $User = $requete->fetch(PDO::FETCH_ASSOC);

            $this->id = $id;
            $this->lastname = $User["nom"];
            $this->firstname = $User["prenom"];
            $this->birth = $User["dateNaiss"];
            $this->password = $User['mdp'];
            $this->idPoste = $User["idPoste"];
            $this->email = $User["email"];
            $this->idEquipe = $User["idEquipe"];
            $this->idOrganisation = $User["idOrganisation"];
        }
    }

    //! SETTER

    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
    }

    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
    }

    public function setBirth($birth)
    {
        $this->birth = $birth;
    }

    public function setPassword($password)
    {
        $this->password = hash($password, PASSWORD_BCRYPT);
    }

    public function setIdPoste($idPoste)
    {
        $this->idPoste = $idPoste;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function setIdEquipe($idEquipe)
    {
        $this->idEquipe = $idEquipe;
    }


    //! GETTER

    public function getId()
    {
        return $this->idUser;
    }

    public function getLastname()
    {
        return $this->lastname;
    }

    public function getFirstname()
    {
        return $this->firstname;
    }

    public function getBirth()
    {
        return $this->birth;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getIdPoste()
    {
        return $this->idPoste;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getIdEquipe()
    {
        return $this->idEquipe;
    }

    public function getIdOrganisation()
    {
        return $this->idOrganisation;
    }

    
    //! METHODES

    public function verifEmail($eU)
    {
        $sql = "SELECT email FROM utilisateurs WHERE email = ?";
        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$eU]);

        if($requete->rowcount() > 0)
        {
            return true;
        } 
        else 
        {
            return false;
        }
    }


    //! FETCH

    public function fetchAll($idOrganisation = $this->idOrganisation)
    {
        $sql = "SELECT * FROM utilisateurs WHERE idOrganisation = ?";
        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$idOrganisation]);
        
        return $requete->fetchAll(PDO::FETCH_ASSOC);
    }

    public function fetchByEquipe($idEquipe)
    {
        $sql = "SELECT * FROM utilisateurs WHERE idEquipe = ?";
        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$idEquipe]);

        return $requete->fetchAll(PDO::FETCH_ASSOC);
    }
    

    //! INSERT

    public function create($firstname, $lastname, $birth, $idPoste, $email, $idEquipe, $idOrganisation)
    {
        // ON CREE UN MDP TEMPORAIRE A L'UTILISATEUR
        $mdp = $this->generateRandomString(6);
        $mdptemp = $mdp;
        $mdp = password_hash($mdp, PASSWORD_BCRYPT);
                        
        $sql = "INSERT INTO utilisateurs (nom, prenom, dateNaiss, mdp, idPoste, email, idEquipe, idOrganisation) ";
        $sql .= "VALUES (?,?,?,?,?,?,?,?)";
        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$firstname, $lastname, $birth, $idPoste, $email, $idEquipe, $idOrganisation]);

        $sql = "SELECT MAX(idUtilisateur) FROM utilisateurs";
        $requete = $this->getBdd()->prepare($sql);
        $requete->execute();

        $idUser = $requete->fetch(PDO::FETCH_ASSOC);
        $idUser = $idUser["idUtilisateur"];

        $sql = "SELECT * FROM utilisateurs WHERE idUtilisateur = ?";
        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$idUser]);

        return $mdptemp;
    }


    //! UPDATE

    public function updateFirstname($firstname)
    {
        $sql = "UPDATE FROM utilisateurs SET prenom = ? WHERE idUtilisateur = ?";
        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$firstname, $this->idUser]);
    }

    public function updateLastname($lastname)
    {
        $sql = "UPDATE FROM utilisateurs SET nom = ? WHERE idUtilisateur = ?";
        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$lastname, $this->idUser]);
    }

    public function updatePoste($idPoste)
    {
        $sql = "UPDATE FROM utilisateurs SET idPoste = ? WHERE idUtilisateur = ?";
        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$idPoste, $this->idUser]);
    }

    public function updateEquipe($idEquipe)
    {
        $sql = "UPDATE FROM utilisateurs SET idEquipe = ? WHERE idUtilisateur = ?";
        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$idEquipe, $this->idUser]);
    }

    public function updatePassword($password)
    {
        $sql = "UPDATE FROM utilisateurs SET mdp = ? WHERE idUtilisateur = ?";
        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$password, $this->idUser]);
    }

    public function updateEmail($email)
    {
        $sql = "UPDATE utilisateurs SET email = ? WHERE idUtilisateur = ?";
        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$email, $this->idUser]);
    }

    public function updateBirth($birth)
    {
        $sql = "UPDATE utilisateurs SET dateNaiss = ? WHERE idUtilisateur = ?";
        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$birth, $this->idUser]);
    }
}
