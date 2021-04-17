<?php
class Utilisateur extends Modele
{
    private $idUtilisateur;
    private $nom;
    private $prenom;
    private $dateNaiss;
    private $mdp;
    private $idPoste;
    private $email;
    private $idEquipe;
    private $idOrganisation;

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
    }

    public function setPrenomUser($pU)
    {
        $this->prenom = $pU;
    }

    public function setDateNaissUser($dN)
    {
        $this->dateNaiss = $dN;
    }

    public function setMdpUser($mU)
    {
        $this->mdp = hash($mU, PASSWORD_BCRYPT);
    }

    public function setIdPosteUser($idP)
    {
        $this->idPoste = $idP;
    }

    public function setEmailUser($eU)
    {
        $this->email = $eU;
    }

    public function setIdEquipe($idE)
    {
        $this->idEquipe = $idE;
    }

    public function setIdOrganisation($idO)
    {
        $this->idOrganisation = $idO;
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
    public function recupererHashMdpUser()
    {
        $requete = $this->getBdd()->prepare("SELECT mdp FROM utilisateurs WHERE idUtilisateur = ?");
        $requete->execute([$_SESSION["idUtilisateur"]]);
        return $requete->fetch(PDO::FETCH_ASSOC);
    }

    public function modifierMdpUtilisateur($newmdp)
    {
        $newmdp = password_hash($newmdp, PASSWORD_BCRYPT);
        $requete = $this->getBdd()->prepare("UPDATE utilisateurs SET mdp = ? WHERE idUtilisateur = ?");
        $requete->execute([$newmdp,$_SESSION["idUtilisateur"]]);
    }

    public function recupInfosUtilisateurs($idOrganisation)
    {
        $requete = $this->getBdd()->prepare("SELECT * FROM utilisateurs LEFT JOIN equipes USING(idEquipe) LEFT JOIN postes USING(idPoste) WHERE utilisateurs.idOrganisation = ?");
        $requete->execute([$idOrganisation]);
        return $requete->fetchAll(PDO::FETCH_ASSOC);
    }

    public function recupChefEquipe($idChefEquipe)
    {
        $requete = $this->getBdd()->prepare("SELECT nom, prenom FROM utilisateurs WHERE idUtilisateur = ?");
        $requete->execute([$idChefEquipe]);
        return $requete->fetchAll(PDO::FETCH_ASSOC);
    }

    public function recupInfosUtilisateur($idUser)
    {
        $requete = $this->getBdd()->prepare ("SELECT * FROM utilisateurs WHERE idUtilisateur = ?");
        $requete->execute($idUser);
        return $requete->fetch(PDO::FETCH_ASSOC);
    }

    public function modifierEquipeUtilisateur($idEquipe, $idUser)
    {
        $requete = $this->getBdd()->prepare("UPDATE utilisateurs SET idEquipe = ? WHERE idUtilisateur = ?");
        $requete->execute([$idEquipe, $idUser]);
    }

    public function modifierPosteUtilisateur($idPoste, $idUser)
    {
        $requete = $this->getBdd()->prepare("UPDATE utilisateurs SET idPoste = ? WHERE idUtilisateur = ?");
        $requete->execute([$idPoste, $idUser]);
    }

    public function modifierNom($nom,$idUser)
    {
        $requete = $this->getBdd()->prepare("UPDATE utilisateurs SET nom = ? WHERE idUtilisateur = ?");
        $requete->execute([$nom,$idUser]);
    }

    public function modifierPrenom($prenom,$idUser)
    {
        $requete = $this->getBdd()->prepare("UPDATE utilisateurs SET prenom = ? WHERE idUtilisateur = ?");
        $requete->execute([$prenom,$idUser]);
    }

    public function verifEmailUtilisateur($email)
    {
        $requete = $this->getBdd()->prepare("SELECT email FROM utilisateurs WHERE email = ?");
        $requete->execute([$email]);

        if($requete->rowcount() > 0)
        {
            return true;
        } else {
            return false;
        }
    }

    public function creerUtilisateur($nom,$prenom,$dateNaiss,$idPoste,$email,$idEquipe,$idOrganisation)
    {
        try {

            $mdp = $this->generateRandomString(6);
            $mdptemp = $mdp;
            $mdp = password_hash($mdp, PASSWORD_BCRYPT);

                            
            $requete = $this->getBdd()->prepare("INSERT INTO utilisateurs (nom, prenom, dateNaiss, mdp, idPoste, email, idEquipe, idOrganisation)
            VALUES (?,?,?,?,?,?,?,?)");
            $requete->execute([$nom,$prenom,$dateNaiss,$mdp,$idPoste,$email,$idEquipe,$idOrganisation]);
            return $mdptemp;

        } catch (exception $e) {
            return false;
        }
    }

    public function recupUtilisateurMail($mail)
    {
        $requete = $this->getBdd()->prepare("SELECT * FROM utilisateurs WHERE email = ?");
        $requete->execute([$mail]);
        return $requete->fetch(PDO::FETCH_ASSOC);
    }


    public function recupUtilisateursEquipe($idEquipe)
    {
        $requete = $this->getBdd()->prepare("SELECT * FROM utilisateurs WHERE idEquipe = ?");
        $requete->execute([$idEquipe]);
        return $requete->fetchAll(PDO::FETCH_ASSOC);
    }

    public function recupUtilisateursPoste($idPoste)
    {
        $requete = $this->getBdd()->prepare("SELECT * FROM utilisateurs WHERE idPoste = ?");
        $requete->execute([$idPoste]);
        return $requete->fetchAll(PDO::FETCH_ASSOC);
    }

    public function recupererPosteUtilisateur($idUtilisateur)
    {
        $requete = $this->getBdd()->prepare("SELECT postes.nomPoste as nomPoste FROM utilisateurs INNER JOIN postes USING(idPoste) WHERE idUtilisateur = ?");
        $requete->execute([$idUtilisateur]);
        return $requete->fetch(PDO::FETCH_ASSOC);
    }

    public function recupIdChefProjet($nomChef, $prenomChef)
    {
        $requete = $this->getBdd()->prepare("SELECT idUtilisateur FROM utilisateurs WHERE nom = ? AND prenom = ?");
        $requete->execute([$nomChef, $prenomChef]);
        return $requete->fetch(PDO::FETCH_ASSOC);
    }
}
