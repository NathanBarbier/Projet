<?php

Class Poste extends Modele
{
    private $idPoste;
    private $nom;
    private $idOrganisation;
    private $idRole;

    public function __construct($idPoste = null)
    {
        if($idPoste !== null)
        {
            $sql = "SELECT * FROM postes WHERE idPoste = ?";
            $requete = $this->getBdd()->prepare($sql);
            $requete->execute([$idPoste]);

            $poste = $requete->fetch(PDO::FETCH_ASSOC);

            $this->idPoste = $idPoste;
            $this->nom = $poste["nomPoste"];
            $this->idOrganisation = $poste["idOrganisation"];
            $this->idRole = $poste["idRole"];
        }
    }


    //! GETTER

    public function getId()
    {
        return $this->idPoste;
    }
    
    public function getNom()
    {
        return $this->nom;
    }

    public function getIdOrganisation()
    {
        return $this->idOrganisation;
    }

    public function getIdRole()
    {
        return $this->idRole;
    }


    //! SETTER

    public function setName($nom)
    {
        $this->nom = $nom;
    }

    public function setIdRole($idRole)
    {
        $this->idRole = $idRole;
    }


    //! UPDATE

    public function updateName($nom)
    {
        $sql = "UPDATE FROM postes SET nomPoste = ? WHERE idPoste = ?";
        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$nom, $this->idPoste]);
    }

    public function updateIdRole($idRole)
    {
        $sql ="UPDATE postes SET idPoste = ? WHERE idPoste =  ?";
        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$idRole, $this->idPoste]);
    }


    //! DELETE 

    /**
    *   Supprime le poste de l'organisation et réaffecte le poste "indéfini" aux utilisateurs ayant ce poste
    */
    public function delete($idPoste = $this->idPoste)
    {
        // SELECTION DE L'ID DU POSTE "INDEFINI"
        $sql = "SELECT idPoste FROM postes WHERE idOrganisation = ? LIMIT 1";
        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$this->idOrganisation]);
        $indefini = $requete->fetch(PDO::FETCH_ASSOC);
        
        // REAFFECTATION AU POSTE "INDEFINI" POUR LES UTILISATEURS AYANT LE POSTE EN SUPPRESSION
        $sql = "UPDATE utilisateurs SET idPoste = ? WHERE idPoste = ?";
        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$indefini["idPoste"], $idPoste]);

        // SUPPRESSION DU POSTE
        $sql = "DELETE FROM postes WHERE idPoste = ?";
        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$idPoste]);
    }

    
    //! INSERT
    /**
     * Insère un poste dans la Bdd
     */
    public function create($nom, $idOrganisation, $idRole)
    {
        $sql = "INSERT INTO postes (nomPoste,idOrganisation,idRole) VALUES (?,?,?)";
        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$nom, $idOrganisation, $idRole]);
    }


    //! FETCH
    /**
     * Retourne tous les postes de l'organisation
     */
    public function fetchAll($idOrganisation = $this->idOrganisation)
    {
        $sql = "SELECT * FROM Postes WHERE idOrganisation = ?";
        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$idOrganisation]);
        
        return $requete->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Retourne le poste correspondant à son id
     */
    public function fetch($id)
    {
        $sql = "SELECT FROM Postes WHERE idPoste = ?";
        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$id]);

        return $requete->fetch(PDO::FETCH_ASSOC);
    }

}

