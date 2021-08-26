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
            $requete->execute([$this->idEquipe]);

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
        return $this->nomEquipe;
    }

    public function getId()
    {
        return $this->idEquipe;
    }

    public function getChef()
    {
        return $this->chefEquipe;
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

    public function fetchAll($idOrganisation = null)
    {
        $idOrganisation = $idOrganisation == null ? $this->getIdOrganisation() : $idOrganisation;

        $sql = "SELECT * FROM equipes WHERE idOrganisation = ?";
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

    public function create($nom, $idOrganisation = null)
    {
        $idOrganisation = $idOrganisation == null ? $this->getIdOrganisation() : $idOrganisation; 

        $sql = "INSERT INTO equipes (nomEquipe, idOrganisation) VALUES (?,?)";
        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$nom, $idOrganisation]);
    } 


    //! UPDATE

    public function updateName($name)
    {
        $sql = "UPDATE equipes SET nomEquipe = ? WHERE idEquipe =  ?";
        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$name, $this->idEquipe]);
    }

    public function updateChef($chef)
    {
        $sql = "UPDATE equipes SET chefEquipe = ? WHERE idEquipe =  ?";
        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$chef, $this->idEquipe]);
    }
}