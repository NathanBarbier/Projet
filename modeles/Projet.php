<?php

Class Projet extends Modele
{
    private $id;
    private $nom;
    private $type;
    private $dateDebut;
    private $dateRendu;
    private $description;
    private $idOrganisation;
    private $tasks = array();

    public function __construct($idProjet = null)
    {
        if($idProjet !== null)
        {
            $sql = "SELECT p.idProjet, p.nom, p.type, p.DateDebut, p.DateRendu, p.fk_organisation, p.description";
            $sql .= " FROM projets AS p";
            $sql .= " WHERE p.idProjet = ?";

            $requete = $this->getBdd()->prepare($sql);
            $requete->execute([$idProjet]);

            $Projet = $requete->fetch(PDO::FETCH_OBJ);

            // var_dump($Projet);
            // exit;

            if($Projet != false)
            {
                $this->id = $idProjet;
                $this->nom = $Projet->nom;
                $this->type = $Projet->type;
                $this->dateRendu = $Projet->DateRendu;
                $this->dateDebut = $Projet->DateDebut;
                $this->description = $Projet->description;
                $this->idOrganisation = $Projet->fk_organisation;
            }
                
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

    public function getIdOrganisation()
    {
        return $this->idOrganisation;
    }

    public function getDescritpion()
    {
        return $this->description;
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

    public function setIdOrganisation($idOrganisation)
    {
        $this->idOrganisation = $idOrganisation;
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

    public function fetchAll($idOrganisation = null)
    {
        if($idOrganisation == null)
        {
            $idOrganisation = $this->idOrganisation;
        }

        // $idOrganisation = $idOrganisation ?? $this->idOrganisation;

        $sql = "SELECT p.idProjet, p.nom, p.type, p.DateDebut, p.DateRendu, p.description, p.fk_organisation";
        $sql .= " FROM projets AS p";
        $sql .= " WHERE p.fk_organisation = ?";

        // var_dump($idOrganisation);
        // exit;

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$idOrganisation]);

        $lines = $requete->fetchAll(PDO::FETCH_OBJ);

        // var_dump($lines);
        // exit;

        return $lines;
    }

    public function fetchByEquipe($idEquipe)
    {
        $sql = "SELECT e.nomEquipe, p.nom, p.type";
        $sql .= " FROM travaille_sur";
        $sql .= " INNER JOIN equipes AS e USING(idEquipe)"; 
        $sql .= " INNER JOIN projets AS p USING(idProjet)";
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
    public function create($titre, $type, $deadline = NULL, $description, $idOrganisation = false)
    {
        $idOrganisation = $idOrganisation ?? $this->getIdOrganisation();
        $status = array();

        $sql = "INSERT INTO map_columns (name, fk_project)";
        $sql .= " VALUES ('Open', ?),('Ready', ?),('In progress', ?),('Closed', ?)";

        $requete = $this->getBdd()->prepare($sql);
        $status[] = $requete->execute([$idOrganisation, $idOrganisation, $idOrganisation, $idOrganisation]);

        $sql = "INSERT INTO projets (nom, type, DateDebut, DateRendu, description, fk_organisation)";
        $sql .= " VALUES (?,?,NOW(),?,?,?)";

        $requete = $this->getBdd()->prepare($sql);

        $status[] = $requete->execute([$titre, $type, $deadline, $description, $idOrganisation]);

        if(in_array(false, $status))
        {
            return false;
        }
        else
        {
            return true;
        }
    }
}