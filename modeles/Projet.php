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
    }

    public function setNomProjet($nom)
    {
        $this->nom = $nom ;
    }

    public function setTypeProjet($type)
    {
        $this->type = $type ;
    }

    public function setDateRenduProjet($dateRendu)
    {
        $this->dateRendu = $dateRendu ;
    }

    public function setDateDebutProjet($dateDebut)
    {
        $this->dateDebut = $dateDebut ;
    }

    public function setIdClient($idClient)
    {
        $this->idClient = $idClient ;
    }

    public function setEtatProjet($etat)
    {
        $this->etat = $etat ;
    }

    public function setChefProjet($chefProjet)
    {
        $this->chefProjet = $chefProjet ;
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