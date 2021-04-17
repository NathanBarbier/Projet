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
            $requete = $this->getBdd()->prepare("SELECT * FROM postes WHERE idPoste = ?");
            $requete->execute([$idPoste]);
            $poste = $requete->fetch(PDO::FETCH_ASSOC);

            $this->idPoste = $idPoste;
            $this->nom = $poste["nomPoste"];
            $this->idOrganisation = $poste["idOrganisation"];
            $this->idRole = $poste["idRole"];
        }
    }
    // GETTER
    public function getIdPoste()
    {
        return $this->idPoste;
    }
    
    public function getNomPoste()
    {
        return $this->nom;
    }

    public function getIdOrganisationPoste()
    {
        return $this->idOrganisation;
    }

    public function getIdRolePoste()
    {
        return $this->idRole;
    }

    // SETTER
    public function setNomPoste($nom)
    {
        $this->nom = $nom;
        $requete = $this->getBdd()->prepare("UPDATE postes SET nom = ? WHERE idPoste =  ?");
        $requete->execute([$this->nom, $this->idPoste]);
    }

    public function setIdRolePoste($idRole)
    {
        $this->idRole = $idRole;
        $requete = $this->getBdd()->prepare("UPDATE postes SET idPoste = ? WHERE idPoste =  ?");
        $requete->execute([$this->idRole, $this->idPoste]);
    }

}

