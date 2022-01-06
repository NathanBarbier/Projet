<?php
class User extends Modele
{
    private $id;
    private $firstname;
    private $lastname;
    private $birth;
    private $password;
    private $email;
    private $idOrganization;
    private $consent;

    public function __construct($id = null)
    {
        if($id != null)
        {
            $sql = "SELECT *"; 
            $sql .= " FROM users"; 
            $sql .= " WHERE rowid = ?";

            $requete = $this->getBdd()->prepare($sql);
            $requete->execute([$id]);

            $User = $requete->fetch(PDO::FETCH_OBJ);

            $this->id = $id;
            $this->lastname = $User->lastname;
            $this->firstname = $User->firstname;
            $this->birth = $User->birth;
            $this->password = $User->password;
            $this->email = $User->email;
            $this->idOrganization = $User->fk_organization;
            $this->consent = $User->consent;
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

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function setConsent(bool $consent)
    {
        $this->consent = $consent;
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

    public function getEmail()
    {
        return $this->email;
    }

    public function getIdorganization()
    {
        return $this->idorganization;
    }

    public function getConsent()
    {
        return $this->consent;
    }
    
    //! METHODES

    /** Check if an email is linked to a user
     * @param string $email the email to check
     * @return boolean true if email is linked to a user, otherwise not linked
     */
    public function checkByEmail(string $email)
    {
        $sql = "SELECT u.email";
        $sql .= " FROM users AS u"; 
        $sql .= " WHERE u.email = ?";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$email]);

        if($requete->rowcount() > 0)
        {
            return true;
        } 
        else 
        {
            return false;
        }
    }
    
    public function checkToken($idUser, $token)
    {
        $sql = "SELECT *";
        $sql .= " FROM users";
        $sql .= " WHERE rowid = ?";
        $sql .= " AND token = ?";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$idUser, $token]);

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

    public function fetchAll($idorganization = null)
    {
        $idorganization = $idorganization == null ? $this->getIdorganization() : $idorganization;

        $sql = "SELECT *";
        $sql .= " FROM users";
        $sql .= " WHERE fk_organization = ?";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$idorganization]);
        
        return $requete->fetchAll(PDO::FETCH_OBJ);
    }

    /** Return users that are not related to a project
     * @param int $projectId The project for which we are looking for unrelated users
     * @param int $idOrganization The organization in which we will search for users.
     * @return List<User>|false unrelated users if any, otherwise false
     */
    public function fetchFreeUsersByProjectId(int $projectId, int $idOrganization)
    {
        $sql = "SELECT u.rowid";
        $sql .= " FROM users AS u";
        $sql .= " LEFT JOIN belong_to AS b ON u.rowid = b.fk_user";
        $sql .= " LEFT JOIN teams AS t ON b.fk_team = t.rowid";
        $sql .= " WHERE t.fk_project = ?";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$projectId]);

        $lines = $requete->fetchAll(PDO::FETCH_OBJ);

        $notFreeUsers = array();
        foreach($lines as $line)
        {
            $notFreeUsers[] = $line->rowid;
        }

        $notFreeUsers = implode("', '", $notFreeUsers);
        
        $sql = "SELECT u.rowid, u.lastname, u.firstname, u.birth, u.password, u.email, u.fk_organization";
        $sql .= " FROM users AS u";
        $sql .= " WHERE u.rowid NOT IN(";
        $sql .= " SELECT rowid";
        $sql .= " FROM users";

        $sql .= " WHERE rowid IN ('".$notFreeUsers."') )"; 
        $sql .= " AND u.fk_organization = ?";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$idOrganization]);        
        
        return $requete->fetchAll(PDO::FETCH_OBJ);
    }

    //* outdated, use instead $Project->getTeams()->getUsers()
    public function fetchByTeam($idTeam)
    {
        $sql = "SELECT u.rowid, u.lastname, u.firstname, u.birth, u.password, u.email, u.fk_organization"; 
        $sql .= " FROM users AS u";
        $sql .= " LEFT JOIN belong_to AS b ON u.rowid = b.fk_user" ;
        $sql .= " WHERE b.fk_team = ?";
        
        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$idTeam]);

        return $requete->fetchAll(PDO::FETCH_OBJ);
    }

    public function fetchByEmail($email)
    {
        $sql = "SELECT *"; 
        $sql .= " FROM users"; 
        $sql .= " WHERE email = ?";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$email]);

        return $requete->fetch(PDO::FETCH_OBJ);
    }
    
    public function fetchById($rowid)
    {
        $sql = "SELECT *";
        $sql .= " FROM users"; 
        $sql .= " WHERE rowid = ?";
        
        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$rowid]);

        return $requete->fetch(PDO::FETCH_OBJ);
    }

    public function fetchByIds(array $usersIds)
    {
        $usersIds = implode("', '", $usersIds);

        $sql = "SELECT *";
        $sql .= " FROM users";
        $sql .= " WHERE rowid IN ('".$usersIds."')";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute();

        return $requete->fetchAll(PDO::FETCH_OBJ);
    }

    //! INSERT

    public function create($firstname, $lastname, $birth, $email, $idorganization, $password)
    {
        $status = array();
                        
        $sql = "INSERT INTO users (lastname, firstname, birth, email, fk_organization, password, consent) ";
        $sql .= "VALUES (?,?,?,?,?,?,?,0)";
        $requete = $this->getBdd()->prepare($sql);


        $status[] = $requete->execute([$lastname, $firstname, $birth, $email, $idorganization, $password]);

        $sql = "SELECT MAX(rowid) as rowid"; 
        $sql .= " FROM users";

        $requete = $this->getBdd()->prepare($sql);
        $status[] = $requete->execute();

        $User = $requete->fetch(PDO::FETCH_OBJ);
        $maxIdUser = $User->rowid;

        $sql = "SELECT *"; 
        $sql .= " FROM users"; 
        $sql .= " WHERE rowid = ?";

        $requete = $this->getBdd()->prepare($sql);
        $status[] = $requete->execute([$maxIdUser]);


        if(in_array(false, $status))
        {
            return false;
        }
        else
        {
            return true;
        }
    }


    //! UPDATE

    public function updateInformations($firstname, $lastname, $email, $idUser = null)
    {
        $idUser = $this->id ?? $idUser;

        $sql = "UPDATE users";
        $sql .= " SET firstname = ?, lastname = ?, email = ?";
        $sql .= " WHERE rowid = ?";

        $requete = $this->getBdd()->prepare($sql);

        return $requete->execute([$firstname, $lastname, $email, $idUser]);
    }

    public function updateFirstname($firstname, $idUser)
    {
        $sql = "UPDATE users";
        $sql .= " SET firstname = ?"; 
        $sql .= " WHERE rowid = ?";

        $requete = $this->getBdd()->prepare($sql);
        return $requete->execute([$firstname, $idUser]);
    }

    public function updateLastname($lastname, $idUser)
    {
        $sql = "UPDATE users";
        $sql .= " SET lastname = ?";
        $sql .= " WHERE rowid = ?";
        
        $requete = $this->getBdd()->prepare($sql);
        return $requete->execute([$lastname, $idUser]);
    }

    public function updatePassword($password, $idUser = null)
    {
        $idUser = $this->id ?? $idUser;

        $sql = "UPDATE users"; 
        $sql .= " SET password = ?"; 
        $sql .= " WHERE rowid = ?";

        $requete = $this->getBdd()->prepare($sql);
        return $requete->execute([$password, $idUser]);
    }

    public function updateEmail($email, $idUser)
    {
        $sql = "UPDATE users"; 
        $sql .= " SET email = ?";
        $sql .= " WHERE rowid = ?";
        
        $requete = $this->getBdd()->prepare($sql);
        return $requete->execute([$email, $idUser]);
    }

    public function updateBirth($birth, $idUser)
    {
        $sql = "UPDATE users"; 
        $sql .= " SET birth = ?";
        $sql .= " WHERE rowid = ?";
        
        $requete = $this->getBdd()->prepare($sql);
        return $requete->execute([$birth, $idUser]);
    }

    public function updateConsent($Consent, $idUser)
    {
        $sql = "UPDATE users";
        $sql .= " SET consent = ?,";
        $sql .= " consent_date = NOW()";
        $sql .= " WHERE rowid = ?";

        $requete = $this->getBdd()->prepare($sql);
        return $requete->execute([$Consent, $idUser]);
    }


    //! DELETE

    public function delete($idUser)
    {
        $sql = "DELETE FROM tasks_members WHERE fk_user = ?;";
        $sql .= "DELETE FROM tasks_comments WHERE fk_user = ?;";
        $sql .= "DELETE FROM belong_to WHERE fk_user = ?;";
        $sql .= "DELETE FROM users WHERE rowid = ?";

        $requete = $this->getBdd()->prepare($sql);
        return $requete->execute([$idUser, $idUser, $idUser, $idUser]);
    }
    
    public function addCookie($idUser, $token)
    {
        $sql = "UPDATE users";
        $sql .= " SET token = ?";
        $sql .= " WHERE rowid = ?";

        $requete = $this->getBdd()->prepare($sql);
        return $requete->execute([$token, $idUser]);
    }
}
