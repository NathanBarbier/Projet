<?php
class Utilisateur extends Modele
{
    protected $idUtilisateur;
    protected $nom;
    protected $prenom;
    protected $dateNaiss;
    protected $mdp;
    protected $idPoste;
    protected $email;
    protected $idEquipe;
    protected $idOrganisation;

    public function __construct($idU = null)
    {
        if($idU != null)
        {
            $requete = $this->getBdd()->prepare("SELECT * FROM utilisateurs WHERE idUtilisateur = ?");
            $requete->execute([$idU]);

            $Utilisateur = $requete->fetch(PDO::FETCH_ASSOC);
            $this->idUtilisateur = $idU;
            $this->nom = $Utilisateur["nom"];
            $this->prenom = $Utilisateur["prenom"];
            $this->dateNaiss = $Utilisateur["dateNaiss"];
            $this->mdp = $Utilisateur['mdp'];
            $this->idPoste = $Utilisateur["idPoste"];
            $this->email = $Utilisateur["email"];
            $this->idEquipe = $Utilisateur["idEquipe"];
            $this->idOrganisation = $Utilisateur["idOrganisation"];
        }
    }

    // SETTER
    public function setNomUser($nU)
    {
        $this->nom = $nU;
        $requete = $this->getBdd()->prepare("UPDATE utilisateurs SET nom = ? WHERE idUtilisateur = ?");
        $requete->execute([$this->nom,$this->idUtilisateur]);
    }

    public function setPrenomUser($pU)
    {
        $this->prenom = $pU;
        $requete = $this->getBdd()->prepare("UPDATE utilisateurs SET prenom = ? WHERE idUtilisateur = ?");
        $requete->execute([$this->prenom,$this->idUtilisateur]);
    }

    public function setDateNaissUser($dN)
    {
        $this->dateNaiss = $dN;
        $requete = $this->getBdd()->prepare("UPDATE utilisateurs SET dateNaiss = ? WHERE idUtilisateur = ?");
        $requete->execute([$this->dateNaiss, $this->idUtilisateur]);
    }

    public function setMdpUser($mU)
    {
        $this->mdp = hash($mU, PASSWORD_BCRYPT);
        $requete = $this->getBdd()->prepare("UPDATE utilisateurs SET mdp = ? WHERE idUtilisateur = ?");
        $requete->execute([$this->mdp,$this->idUtilisateur]);
    }

    public function setIdPosteUser($idP)
    {
        $this->idPoste = $idP;
        $requete = $this->getBdd()->prepare("UPDATE utilisateurs SET idPoste = ? WHERE idUtilisateur = ?");
        $requete->execute([$this->idPoste, $this->idUtilisateur]);
    }

    public function setEmailUser($eU)
    {
        $this->email = $eU;
        $requete = $this->getBdd()->prepare("UPDATE utilisateurs SET email = ? WHERE idUtilisateur = ?");
        $requete->execute([$this->email, $this->idUtilisateur]);
    }

    public function setIdEquipeUser($idE)
    {
        $this->idEquipe = $idE;
        $requete = $this->getBdd()->prepare("UPDATE utilisateurs SET idEquipe = ? WHERE idUtilisateur = ?");
        $requete->execute([$this->idEquipe, $this->idUtilisateur]);
    }

    // GETTER

    public function getIdUser()
    {
        return $this->idUtilisateur;
    }

    public function getNomUser()
    {
        return $this->nom;
    }

    public function getPrenomUser()
    {
        return $this->prenom;
    }

    public function getDateNaissUser()
    {
        return $this->dateNaiss;
    }

    public function getMdpUser()
    {
        return $this->mdp;
    }

    public function getIdPosteUser()
    {
        return $this->idPoste;
    }

    public function getEmailUser()
    {
        return $this->email;
    }

    public function getIdEquipeUser()
    {
        return $this->idEquipe;
    }

    public function getIdOrganisationUser()
    {
        return $this->idOrganisation;
    }

    // METHODES
    // VERIF
    public function verifEmailUser($eU)
    {
        $requete = $this->getBdd()->prepare("SELECT email FROM utilisateurs WHERE email = ?");
        $requete->execute([$eU]);

        if($requete->rowcount() > 0)
        {
            return true;
        } else {
            return false;
        }
    }

    // ADD
    public function addUser($nom,$prenom,$dateNaiss,$idP,$em,$idE,$idO)
    {
        $mdp = $this->generateRandomString(6);
        $mdptemp = $mdp;
        $mdp = password_hash($mdp, PASSWORD_BCRYPT);
                        
        $requete = $this->getBdd()->prepare("INSERT INTO utilisateurs (nom, prenom, dateNaiss, mdp, idPoste, email, idEquipe, idOrganisation)
        VALUES (?,?,?,?,?,?,?,?)");
        $requete->execute([$nom,$prenom,$dateNaiss,$mdp,$idP,$em,$idE,$idO]);
        return $mdptemp;
    }
}
