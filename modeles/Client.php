<?php
Class Client extends Modele 
{
    private $idClient;
    private $nom;

    public function __construct($idClient = null)
    {
        if($idClient !== null)
        {
            $requete = $this->getBdd()->prepare("SELECT * FROM clients WHERE idClient = ?");
            $requete->execute([$idClient]);
            $client = $requete->fetch(PDO::FETCH_ASSOC);
            $this->idClient = $idClient;
            $this->nom = $client["nom"];
        }
    }

    public function getIdCLient()
    {
        return $this->idClient;
    }

    public function getNomCLient()
    {
        return $this->nom;
    }

    public function setNomCLient($nom)
    {
        $this->nom = $nom;
        $requete = $this->getBdd()->prepare("UPDATE clients SET nom = ? WHERE idClient =  ?");
        $requete->execute([$this->nom, $this->idClient]);
    }

    public function verifClient($nomClient)
    {
        $requete = $this->getBdd()->prepare("SELECT * FROM clients WHERE nom = ?");
        $requete->execute([$nomClient]);
        if($requete->fetch(PDO::FETCH_ASSOC) > 0)
        {
            return true;
        } else {
            return false;
        }
    }

    public function insertClient($client)
    {
        $requete = $this->getBdd()->prepare("INSERT INTO clients (nom) VALUES (?)");
        $requete->execute([$client]);
    }

    public function recupIdClient($nomClient)
    {
        $requete = $this->getBdd()->prepare("SELECT idClient FROM clients WHERE nom = ?");
        $requete->execute([$nomClient]);
        return $requete->fetch(PDO::FETCH_ASSOC);
    }

}
?>
