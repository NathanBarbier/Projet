<?php

Class Projet extends Modele
{
    private $id;
    private $nom;
    private $type;
    private $dateDebut;
    private $dateRendu;
    private $etat;
    private $tasks = array();

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

    //! UPDATE

    public function updateId($id)
    {
        $sql = "UPDATE projets";
        $sql .= " SET idProjet = ?"; 
        $sql .= " WHERE idProjet =  ?";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$id, $this->idProjet]);
    }

    public function updateNom($nom)
    {
        $sql = "UPDATE projets";
        $sql .= " SET nom = ?"; 
        $sql .= " WHERE idProjet =  ?";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$nom, $this->idProjet]);
    }

    public function updateType($type)
    {
        $sql = "UPDATE projets";
        $sql .= " SET type = ?";
        $sql .= " WHERE idProjet =  ?";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$type, $this->idProjet]);
    }

    public function updateDateRendu($dateRendu)
    {
        $sql = "UPDATE projets";
        $sql .= " SET dateRendu = ?"; 
        $sql .= " WHERE idProjet =  ?";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$dateRendu, $this->idProjet]);
    }

    public function updateIdClient($idClient)
    {
        $sql = "UPDATE projets";
        $sql .= " SET idClient = ?"; 
        $sql .= " WHERE idProjet =  ?";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$idClient, $this->idProjet]);
    }

    public function updateEtat($etat)
    {
        $sql = "UPDATE projets";
        $sql .= " SET etat = ?";
        $sql .= " WHERE idProjet = ?";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$etat, $this->idProjet]);
    }

    //! FETCH

    public function fetchAll($idOrganisation)
    {
        $sql = "SELECT idProjet, nom, type, DateDebut, DateRendu, Etat";
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
        $sql = "SELECT max(idProjet) AS maxId";
        $sql .=" FROM projets";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute();

        return $requete->fetch(PDO::FETCH_ASSOC);
    }

    public function fetch_members_count($projectId = null)
    {
        $projectId = $this->id ?? $projectId;

        $sql = "SELECT COUNT(u.idUtilisateur) as membersCount";
        $sql .= " FROM projets as p";
        $sql .= " LEFT JOIN travaille_sur as t ON p.idProjet = t.idProjet";
        $sql .= " LEFT JOIN appartient_a as a ON a.fk_equipe = t.idEquipe";
        $sql .= " LEFT JOIN utilisateurs as u ON a.fk_user = u.idUtilisateur";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$projectId]);

        return $requete->fetch(PDO::FETCH_OBJ);
    }

    //! INSERT
    public function create($titre, $type, $deadline, $idClient, $description)
    {
        $sql = "INSERT INTO projets (nom, type, DateDebut, DateRendu, idClient, Etat, description)";
        $sql .= " VALUES (?,?,NOW(),?,?,?,?,?)";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$titre, $type, $deadline, $idClient, 'En cours', $description]);
    }

}