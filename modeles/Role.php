<?php

Class Role extends Modele 
{
    private $idRole;
    private $nom;

    public function __construct($idRole = null)
    {
        if($idRole !== null)
        {
            $requete = $this->getBdd()->prepare("SELECT * FROM roles WHERE idRole = ?");
            $requete->execute([$idRole]);
            $role = $requete->fetch(PDO::FETCH_ASSOC);
            $this->idRole = $idRole;
            $this->nom = $role["nom"];
        }
    }

    public function getIdRole()
    {
        return $this->idRole;
    }

    public function getNomRole()
    {
        return $this->nom;
    }

    public function setIdRole($idRole)
    {
        $this->idRole = $idRole;
    }

    public function setNomRole($nom)
    {
        $this->nom = $nom;
    }

}

function recupererRoles()
{
    $requete = getBdd()->prepare("SELECT * FROM roles");
    $requete->execute();
    return $requete->fetchAll(PDO::FETCH_ASSOC);
}