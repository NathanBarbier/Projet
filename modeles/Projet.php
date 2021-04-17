<?php

Class Projet extends Modele
{
    private $idProjet;
    private $nom;
    private $type;
    private $dateDebut;
    private $dateRendu;
    private $idClient;
    private $etat;
    private $chefProjet;

    public function __construct($idProjet = null)
    {
        if($idProjet !== null)
        {
            $requete = $this->getBdd()->prepare("SELECT * FROM projets WHERE idProjet = ?");
            $requete->execute([$idProjet]);
            $projet = $requete->fetch(PDO::FETCH_ASSOC);

            $this->idProjet = $idProjet;
            $this->nom = $projet["nom"];
            $this->type = $projet["type"];
            $this->dateRendu = $projet["dateRendu"];
            $this->dateDebut = $projet["dateDebut"];
            $this->idClient = $projet["idClient"];
            $this->etat = $projet["etat"];
            $this->chefProjet = $projet["chefProjet"];
        }
    }

    public function getIdProjet()
    {
        return $this->idProjet;
    }

    public function getNomProjet()
    {
        return $this->nom;
    }

    public function getTypeProjet()
    {
        return $this->type;
    }

    public function getDateRenduProjet()
    {
        return $this->dateRendu;
    }

    public function getDateDebutProjet()
    {
        return $this->dateDebut;
    }

    public function getIdClient()
    {
        return $this->idClientt;
    }

    public function getEtatProjet()
    {
        return $this->etat;
    }

    public function getChefProjet()
    {
        return $this->chefProjet;
    }

    public function setIdProjet($idProjet)
    {
        $this->idProjet = $idProjet ;
        $requete = $this->getBdd()->prepare("UPDATE projets SET idProjet = ? WHERE idProjet =  ?");
        $requete->execute([$this->idProjet, $this->idProjet]);
    }

    public function setNomProjet($nom)
    {
        $this->nom = $nom ;
        $requete = $this->getBdd()->prepare("UPDATE projets SET nom = ? WHERE idProjet =  ?");
        $requete->execute([$this->nom, $this->idProjet]);
    }

    public function setTypeProjet($type)
    {
        $this->type = $type ;
        $requete = $this->getBdd()->prepare("UPDATE projets SET type = ? WHERE idProjet =  ?");
        $requete->execute([$this->type, $this->idProjet]);
    }

    public function setDateRenduProjet($dateRendu)
    {
        $this->dateRendu = $dateRendu ;
        $requete = $this->getBdd()->prepare("UPDATE projets SET dateRendu = ? WHERE idProjet =  ?");
        $requete->execute([$this->dateRendu, $this->idProjet]);
    }

    public function setIdClient($idClient)
    {
        $this->idClient = $idClient ;
        $requete = $this->getBdd()->prepare("UPDATE projets SET idClient = ? WHERE idProjet =  ?");
        $requete->execute([$this->idClient, $this->idProjet]);
    }

    public function setEtatProjet($etat)
    {
        $this->etat = $etat ;
        $requete = $this->getBdd()->prepare("UPDATE projets SET etat = ? WHERE idProjet =  ?");
        $requete->execute([$this->etat, $this->idProjet]);
    }

    public function setChefProjet($chefProjet)
    {
        $this->chefProjet = $chefProjet ;
        $requete = $this->getBdd()->prepare("UPDATE projets SET chefProjet = ? WHERE idProjet =  ?");
        $requete->execute([$this->chefProjet, $this->idProjet]);
    }

}

function recupProjetParEquipe($idEquipe)
{
    $requete = getBdd()->prepare("SELECT equipes.nomEquipe, projets.nom, projets.type from travaille_sur inner join equipes using(idEquipe) inner join projets using(idProjet) where idEquipe = ?");
    $requete->execute([$idEquipe]);
    return $requete->fetchAll(PDO::FETCH_ASSOC);
} 

function recupMaxIdProjets()
{
    $requete = getBdd()->prepare("SELECT max(idProjet) AS maxId FROM projets");
    $requete->execute();
    return $requete->fetch(PDO::FETCH_ASSOC);
}

function creerProjet($titre, $type, $deadline, $idClient, $chefProjet)
{
    $requete = getBdd()->prepare("INSERT INTO projets (nom, type, DateDebut, DateRendu, idClient, Etat, chefProjet) VALUES (?, ?, NOW(), ?,?, 'En cours', ?)");
    $requete->execute([$titre, $type, $deadline, $idClient, $chefProjet]);
}

function addEquipesProjet($idProjet, $idEquipe)
{
    $requete = getBdd()->prepare("INSERT INTO travaille_sur (idProjet, idEquipe) VALUES (?,?)");
    $requete->execute([$idProjet, $idEquipe]);
}