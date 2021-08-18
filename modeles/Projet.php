<?php

Class Projet extends Modele
{
    private $id;
    private $nom;
    private $type;
    private $dateDebut;
    private $dateRendu;
    private $etat;
    private $chef;

    public function __construct($idProjet = null)
    {
        if($idProjet !== null)
        {
            $sql = "SELECT * FROM projets WHERE idProjet = ?";
            $requete = $this->getBdd()->prepare($sql);
            $requete->execute([$idProjet]);

            $projet = $requete->fetch(PDO::FETCH_ASSOC);

            $this->idProjet = $idProjet;
            $this->nom = $projet["nom"];
            $this->type = $projet["type"];
            $this->dateRendu = $projet["DateRendu"];
            $this->dateDebut = $projet["DateDebut"];
            $this->etat = $projet["Etat"];
            $this->chef = $projet["chefProjet"];
        }
    }

    //! GETTER
    public function getIdProjet()
    {
        return $this->idProjet;
    }

    public function getNom()
    {
        return $this->nom;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getDateRendu()
    {
        return $this->dateRendu;
    }

    public function getDateDebut()
    {
        return $this->dateDebut;
    }

    public function getIdClient()
    {
        return $this->idClient;
    }

    public function getEtat()
    {
        return $this->etat;
    }

    public function getChef()
    {
        return $this->chef;
    }
    

    //! SETTER
    public function setId($id)
    {
        $this->id = $id;
    }

    public function setNom($nom)
    {
        $this->nom = $nom;
    }

    public function setType($type)
    {
        $this->type = $type;
    }

    public function setDateRendu($dateRendu)
    {
        $this->dateRendu = $dateRendu;
    }

    public function setIdClient($idClient)
    {
        $this->idClient = $idClient;
    }

    public function setEtat($etat)
    {
        $this->etat = $etat;
    }

    public function setChef($chef)
    {
        $this->chef = $chef;
    }

    //! UPDATE

    public function updateId($id)
    {
        $sql = "UPDATE projets SET idProjet = ? WHERE idProjet =  ?";
        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$id, $this->idProjet]);
    }

    public function updateNom($nom)
    {
        $sql = "UPDATE projets SET nom = ? WHERE idProjet =  ?";
        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$nom, $this->idProjet]);
    }

    public function updateType($type)
    {
        $sql = "UPDATE projets SET type = ? WHERE idProjet =  ?";
        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$type, $this->idProjet]);
    }

    public function updateDateRendu($dateRendu)
    {
        $sql = "UPDATE projets SET dateRendu = ? WHERE idProjet =  ?";
        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$dateRendu, $this->idProjet]);
    }

    public function updateIdClient($idClient)
    {
        $sql = "UPDATE projets SET idClient = ? WHERE idProjet =  ?";
        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$idClient, $this->idProjet]);
    }

    public function updateEtat($etat)
    {
        $sql = "UPDATE projets SET etat = ? WHERE idProjet = ?";
        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$etat, $this->idProjet]);
    }

    public function updateChef($chef)
    {
        $sql = "UPDATE projets SET chefProjet = ? WHERE idProjet =  ?";
        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$chef, $this->idProjet]);
    }

    //! FETCH

    public function fetchAll($idOrganisation)
    {
        $sql = "SELECT idProjet, nom, type, DateDebut, DateRendu, Etat, chefProjet";
        $sql .= " FROM projets";
        $sql .= " INNER JOIN travaille_sur USING(idProjet)";
        $sql .= " INNER JOIN equipes USING(idEquipe)";
        $sql .= " WHERE equipes.idOrganisation = ?";
        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$idOrganisation]);

        return $requete->fetchAll(PDO::FETCH_ASSOC);
    }

    public function fetchByEquipe($idEquipe)
    {
        $sql = "SELECT equipes.nomEquipe, projets.nom, projets.type";
        $sql .= " FROM travaille_sur";
        $sql .= " INNER JOIN equipes USING(idEquipe)"; 
        $sql .= " INNER JOIN projets USING(idProjet)";
        $sql .= " WHERE idEquipe = ?";
        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$idEquipe]);

        return $requete->fetchAll(PDO::FETCH_ASSOC);
    } 

    public function fetchMaxId()
    {
        $sql = "SELECT max(idProjet) AS maxId FROM projets";
        $requete = $this->getBdd()->prepare($sql);
        $requete->execute();

        return $requete->fetch(PDO::FETCH_ASSOC);
    }


    //! INSERT
    public function create($titre, $type, $deadline, $idClient, $chefProjet, $description)
    {
        $sql = "INSERT INTO projets (nom, type, DateDebut, DateRendu, idClient, Etat, chefProjet, description)";
        $sql .= " VALUES (?,?,NOW(),?,?,?,?,?)";
        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$titre, $type, $deadline, $idClient, 'En cours', $chefProjet, $description]);
    }

}