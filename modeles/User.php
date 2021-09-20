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
        return $this->id;
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

    public function verifEmail($userEmail)
    {
        $sql = "SELECT email";
        $sql .= " FROM utilisateurs"; 
        $sql .= " WHERE email = ?";
        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$userEmail]);

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

    public function fetchAll($idOrganisation = null)
    {
        $idOrganisation = $idOrganisation == null ? $this->getIdOrganisation() : $idOrganisation;

        $sql = "SELECT *";
        $sql .= " FROM utilisateurs";
        $sql .= " WHERE idOrganisation = ?";

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

    public function fetchByEmail($email)
    {
        $sql = "SELECT *"; 
        $sql .= " FROM utilisateurs"; 
        $sql .= " WHERE email = ?";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$email]);

        return $requete->fetch(PDO::FETCH_ASSOC);
    }
    
    public function fetchByLastnameAndFirstname($lastname, $firstname, $idOrganisation)
    {
        $sql = "SELECT * FROM utilisateurs";
        $sql.= "WHERE nom = ? && prenom = ? && idOrganisation = ?";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$$lastname, $firstname, $idOrganisation]);

        return $requete->fetch(PDO::FETCH_ASSOC);
    }

    //! INSERT

    public function create($firstname, $lastname, $birth, $idPoste, $idEquipe, $email, $idOrganisation)
    {
        $status = array();
        // ON CREE UN MDP TEMPORAIRE A L'UTILISATEUR
        // $mdp = $this->generateRandomString(6);
        // $mdptemp = $mdp;
        $mdp = "motdepasse";
        $mdp = password_hash($mdp, PASSWORD_BCRYPT);
                        
        $sql = "INSERT INTO utilisateurs (nom, prenom, dateNaiss, idPoste, email, idEquipe, idOrganisation, mdp) ";
        $sql .= "VALUES (?,?,?,?,?,?,?,?)";
        $requete = $this->getBdd()->prepare($sql);


        $status[] = $requete->execute([$lastname, $firstname, $birth, $idPoste, $email, $idEquipe, $idOrganisation, $mdp]);

        $sql = "SELECT MAX(idUtilisateur) FROM utilisateurs";
        $requete = $this->getBdd()->prepare($sql);
        $status[] = $requete->execute();

        $idUser = $requete->fetch(PDO::FETCH_ASSOC);
        $idUser = $idUser["idUtilisateur"];

        $sql = "SELECT * FROM utilisateurs WHERE idUtilisateur = ?";
        $requete = $this->getBdd()->prepare($sql);
        $status[] = $requete->execute([$idUser]);


        if(in_array(false, $status))
        {
            return false;
        }
        else
        {
            // return $mdptemp;
            return $mdp;
        }
    }


    //! UPDATE

    public function updateFirstname($firstname, $idUser)
    {
        $sql = "UPDATE utilisateurs";
        $sql .= " SET prenom = ?"; 
        $sql .= " WHERE idUtilisateur = ?";
        $requete = $this->getBdd()->prepare($sql);
        return $requete->execute([$firstname, $idUser]);
    }

    public function updateLastname($lastname, $idUser)
    {
        $sql = "UPDATE utilisateurs SET nom = ? WHERE idUtilisateur = ?";
        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$lastname, $idUser]);
    }

    public function updatePoste($idPoste, $idUser)
    {
        $sql = "UPDATE utilisateurs SET idPoste = ? WHERE idUtilisateur = ?";
        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$idPoste, $idUser]);
    }

    public function updateEquipe($idEquipe, $idUser)
    {
        $sql = "UPDATE utilisateurs SET idEquipe = ? WHERE idUtilisateur = ?";
        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$idEquipe, $idUser]);
    }

    public function updatePassword($password, $idUser)
    {
        $sql = "UPDATE utilisateurs SET mdp = ? WHERE idUtilisateur = ?";
        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$password, $idUser]);
    }

    public function updateEmail($email, $idUser)
    {
        $sql = "UPDATE utilisateurs SET email = ? WHERE idUtilisateur = ?";
        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$email, $idUser]);
    }

    public function updateBirth($birth, $idUser)
    {
        $sql = "UPDATE utilisateurs SET dateNaiss = ? WHERE idUtilisateur = ?";
        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$birth, $idUser]);
    }


    //! DELETE

    public function delete($idUser)
    {
        $sql = "DELETE FROM utilisateurs";
        $sql .= " WHERE idUtilisateur = ?";

        $requete = $this->getBdd()->prepare($sql);
        $status = $requete->execute([$idUser]);

        return $status;
    }
}
