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
    }

}

function recupererClientOrganisation($idOrganisation)
{
    $requete = getBdd()->prepare("SELECT clients.nom FROM clients LEFT JOIN projets USING(idClient) LEFT JOIN travaille_sur USING(idProjet) LEFT JOIN equipes USING(idEquipe) WHERE equipes.idOrganisation = ?");
    $requete->execute([$idOrganisation]);
    return $requete->fetchAll(PDO::FETCH_ASSOC);
}

function verifClient($nomClient)
{
    $requete = getBdd()->prepare("SELECT * FROM clients WHERE nom = ?");
    $requete->execute([$nomClient]);
    if($requete->fetch(PDO::FETCH_ASSOC) > 0)
    {
        return true;
    } else {
        return false;
    }
}

function insertClient($client)
{
    $requete = getBdd()->prepare("INSERT INTO clients (nom) VALUES (?)");
    $requete->execute([$client]);
}

function recupIdClient($nomClient)
{
    $requete = getBdd()->prepare("SELECT idClient FROM clients WHERE nom = ?");
    $requete->execute([$nomClient]);
    return $requete->fetch(PDO::FETCH_ASSOC);
}
?>
