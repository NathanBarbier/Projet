<?php
class Equipe extends Modele
{
    private $id;
    private $nom;
    private $idOrganisation;
    private $chef;
    private $membres = [];

    public function __construct($idE = null)
    {
        if($idE != null)
        {
            $sql = "SELECT * FROM equipes WHERE idEquipe = ?";
            $requete = $this->getBdd()->prepare($sql);
            $requete->execute([$idE]);

            $equipe = $requete->fetch(PDO::FETCH_ASSOC);

            $this->id = $idE;
            $this->nom = $equipe["nomEquipe"];
            $this->idOrganisation = $equipe["idOrganisation"];
            $this->chef = $equipe["chefEquipe"];

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

    public function setChef($chef)
    {
        $this->chef = $chef;
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

    public function getChef()
    {
        return $this->chef;
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

    public function fetchChef($idEquipe)
    {
        $sql = "SELECT utilisateurs.nom, utilisateurs.prenom, utilisateurs.email"; 
        $sql .= " FROM equipes";
        $sql .= " INNER JOIN utilisateurs ON utilisateurs.idUtilisateur = equipes.chefEquipe";
        $sql .= " WHERE equipes.idEquipe = ?";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$idEquipe]);
        
        return $requete->fetch(PDO::FETCH_ASSOC);
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

    public function updateChef($chef)
    {
        $sql = "UPDATE equipes"; 
        $sql .= " SET chefEquipe = ?"; 
        $sql .= " WHERE idEquipe =  ?";

        $requete = $this->getBdd()->prepare($sql);
        return $requete->execute([$chef, $this->idEquipe]);
    }
}