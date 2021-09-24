<?php

Class Position extends Modele
{
    private $id;
    private $name;
    private $idOrganization;
    private $idRole;

    public function __construct($idPosition = null)
    {
        if($idPosition !== null)
        {
            $sql = "SELECT *";
            $sql .= " FROM positions"; 
            $sql .= " WHERE rowid = ?";

            $requete = $this->getBdd()->prepare($sql);
            $requete->execute([$idPosition]);

            $position = $requete->fetch(PDO::FETCH_OBJ);

            $this->id = $idPosition;
            $this->name = $position->name;
            $this->idOrganization = $position->fk_organization;
            $this->idRole = $position->fk_role;
        }
    }


    //! GETTER

    public function getId()
    {
        return $this->id;
    }
    
    public function getName()
    {
        return $this->name;
    }

    public function getIdOrganization()
    {
        return $this->idOrganization;
    }

    public function getIdRole()
    {
        return $this->idRole;
    }


    //! SETTER

    public function setName($name)
    {
        $this->name = $name;
    }

    public function setIdRole($idRole)
    {
        $this->idRole = $idRole;
    }


    //! UPDATE

    public function updateName($name, $idPosition)
    {
        $idPosition = $this->id ?? $idPosition;

        $sql = "UPDATE positions"; 
        $sql .= " SET name = ?"; 
        $sql .= " WHERE rowid = ?";
        
        $requete = $this->getBdd()->prepare($sql);
        return $requete->execute([$name, $idPosition]);
    }

    public function updateIdRole($idRole, $idPosition)
    {
        $idPosition = $this->id ?? $idPosition;

        $sql ="UPDATE positions"; 
        $sql .= " SET fk_role = ?"; 
        $sql .= " WHERE rowid =  ?";
        
        $requete = $this->getBdd()->prepare($sql);
        return $requete->execute([$idRole, $idPosition]);
    }


    //! DELETE 

    /**
    *   Supprime le poste de l'organization et réaffecte le poste "indéfini" aux utilisateurs ayant ce poste
    */
    public function delete($idPosition, $idOrganization)
    {
        $idPosition = $this->getId() ?? $idPosition;
        $idOrganization = $this->idOrganization ?? $idOrganization;

        $status = array();

        // SELECTION DE L'ID DU POSTE "INDEFINI"
        $sql = "SELECT rowid"; 
        $sql .= " FROM positions";
        $sql .= " WHERE fk_organization = ? AND name = 'indéfini'"; 
        $sql .= " LIMIT 1";
        
        $requete = $this->getBdd()->prepare($sql);
        $status[] = $requete->execute([$idOrganization]);
        $indefini = $requete->fetch(PDO::FETCH_OBJ);
        
        // REAFFECTATION AU POSTE "INDEFINI" POUR LES UTILISATEURS AYANT LE POSTE EN SUPPRESSION
        $sql = "UPDATE users"; 
        $sql .= " SET fk_position = ?";
        $sql .= " WHERE fk_position = ?";

        $requete = $this->getBdd()->prepare($sql);
        $status[] = $requete->execute([$indefini->rowid, $idPosition]);

        // SUPPRESSION DU POSTE
        $sql = "DELETE FROM positions";
        $sql .= " WHERE rowid = ?";

        $requete = $this->getBdd()->prepare($sql);
        $status[] = $requete->execute([$idPosition]);

        if(in_array(false, $status))
        {
            return false;
        }
        else
        {
            return true;
        }
    }

    
    //! INSERT
    /**
     * Insère un poste dans la Bdd
     */
    public function create($name, $idorganization, $idRole)
    {
        $sql = "INSERT INTO positions (name, fk_organization, fk_Role)";
        $sql .= " VALUES (?,?,?)";
        
        $requete = $this->getBdd()->prepare($sql);
        return $requete->execute([$name, $idorganization, $idRole]);
    }


    //! FETCH
    /**
     * Retourne tous les postes de l'organization
     */
    public function fetchAll($idorganization = null)
    {
        $idorganization = $idorganization == null ? $this->getIdOrganization() : $idorganization;

        $sql = "SELECT *"; 
        $sql .= " FROM positions";
        $sql .= " WHERE fk_organization = ?";
        
        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$idorganization]);
        
        $lines = $requete->fetchAll(PDO::FETCH_OBJ);

        return $lines;
    }

    /**
     * Retourne le poste correspondant à son id
     */
    public function fetch($id)
    {
        $sql = "SELECT *"; 
        $sql .= " FROM Positions"; 
        $sql .= " WHERE rowid = ?";
        
        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$id]);

        return $requete->fetch(PDO::FETCH_OBJ);
    }

}

