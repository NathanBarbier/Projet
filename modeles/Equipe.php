<?php
class Equipe extends Modele
{
    private $id;
    private $nom;
    private $idOrganisation;
    private $idProjet;
    private $membres = [];

    public function __construct($idE = null)
    {
        if($idE != null)
        {
            $sql = "SELECT *"; 
            $sql .= " FROM equipes"; 
            $sql .= " WHERE idEquipe = ?";
            
            $requete = $this->getBdd()->prepare($sql);
            $requete->execute([$idE]);

            $equipe = $requete->fetch(PDO::FETCH_ASSOC);

            $this->id = $idE;
            $this->nom = $equipe["nomEquipe"];
            $this->idOrganisation = $equipe["idOrganisation"];
            $this->idProjet = $equipe["fk_projet"];

            $sql = "SELECT * FROM utilisateurs WHERE idEquipe = ?";
            $requete = $this->getBdd()->prepare($sql);
            $requete->execute([$this->id]);

            $membres = $requete->fetchAll(PDO::FETCH_ASSOC);

            foreach($membres as $membre)
            {
                $ObjetMembre = new User($membre["idUtilisateur"]);
                $this->membres[] = $ObjetMembre;
            }

        }
    }


    //! SETTER
    
    public function setName($nom)
    {
        $this->nom = $nom;
    }

    public function setIdProjet($idProjet)
    {
        $this->idProjet = $idProjet;
    }


    //! GETTER

    public function getNom()
    {
        return $this->nom;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getIdProjet()
    {
        return $this->idProjet;
    }

    public function getIdOrganisation()
    {
        return $this->idOrganisation;
    }

    public function getMembres()
    {
        return $this->membres;
    }

    public function countMembres()
    {
        return count($this->membres);
    }

    //! FETCH

    public function fetchAll($idOrganisation)
    {
        $idOrganisation = $this->id ?? $idOrganisation;

        $sql = "SELECT *"; 
        $sql .= " FROM equipes"; 
        $sql .= " WHERE idOrganisation = ?";
        
        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$idOrganisation]);

        return $requete->fetchAll(PDO::FETCH_ASSOC);
    }

    public function fetch($idEquipe)
    {
        $sql = "SELECT * FROM equipes WHERE idEquipe = ?";
        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$idEquipe]);

        return $requete->fetch(PDO::FETCH_ASSOC);
    }

    public function fetchNameByUserIdAndProjectId($idUser, $idProjet)
    {
        $sql = "SELECT e.nomEquipe";
        $sql .= " FROM equipes AS e";
        $sql .= " LEFT JOIN travaille_sur as t ON e.idEquipe = t.idEquipe";
        $sql .= " LEFT JOIN projets as p ON t.idProjet = p.idProjet";
        $sql .= " LEFT JOIN appartient_a as a ON t.idEquipe = a.fk_equipe";
        $sql .= " LEFT JOIN utilisateurs as u ON a.fk_user = u.idUtilisateur";
        $sql .= " WHERE u.idUtilisateur = ? AND p.idProjet = ?";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$idUser, $idProjet]);

        return $requete->fetch(PDO::FETCH_OBJ);
    }

    //! INSERT

    public function create($nom, $idOrganisation)
    {
        $idOrganisation = $this->id ?? $idOrganisation; 

        $sql = "INSERT INTO equipes (nomEquipe, idOrganisation)";
        $sql .= " VALUES (?,?)";

        $requete = $this->getBdd()->prepare($sql);
        return $requete->execute([$nom, $idOrganisation]);
    } 


    //! UPDATE

    public function updateName($name)
    {
        $sql = "UPDATE equipes";
        $sql .= " SET nomEquipe = ?";
        $sql .= " WHERE idEquipe =  ?";

        $requete = $this->getBdd()->prepare($sql);
        return $requete->execute([$name, $this->idEquipe]);
    }

    public function updateIdProjet($idProjet)
    {
        $sql = "UPDATE equipes"; 
        $sql .= " SET fk_projet = ?"; 
        $sql .= " WHERE idEquipe =  ?";

        $requete = $this->getBdd()->prepare($sql);
        return $requete->execute([$idProjet, $this->idEquipe]);
    }
}