<?php
Class Client extends Modele 
{
    private $id;
    private $nom;

    public function __construct($idClient = null)
    {
        if($idClient !== null)
        {
            $sql = "SELECT * FROM clients WHERE idClient = ?";
            $requete = $this->getBdd()->prepare($sql);
            $requete->execute([$idClient]);

            $client = $requete->fetch(PDO::FETCH_ASSOC);

            $this->idClient = $idClient;
            $this->nom = $client["nom"];
        }
    }


    //! GETTER

    public function getId()
    {
        return $this->id;
    }

    public function getNom()
    {
        return $this->nom;
    }

    
    //! SETTER
    
    public function setNom($nom)
    {
        $this->nom = $nom;
    }


    //! FETCH
    public function fetchAll($idOrganisation)
    {
        $sql = "SELECT clients.nom as nom, clients.idClient as idClient";
        $sql .= " FROM clients";
        $sql .= " INNER JOIN commande_client USING(idClient)";
        $sql .= " INNER JOIN projets USING(idProjet)";
        $sql .= " INNER JOIN travaille_sur USING(idProjet)";
        $sql .= " INNER JOIN equipes USING(idEquipe)";
        $sql .= " WHERE equipes.idOrganisation = ?";

        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$idOrganisation]);

        return $requete->fetchAll(PDO::FETCH_ASSOC);
    }

    //TODO À CHECK
    public function fetchId($nom)
    {
        $sql = "SELECT idClient FROM clients WHERE nom = ?";
        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$nom]);

        return $requete->fetch(PDO::FETCH_ASSOC);
    }


    //! INSERT

    public function create($nom)
    {
        $sql = "INSERT INTO clients (nom) VALUES (?)";
        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$nom]);
    }


    //! UPDATE

    public function updateNom($nom)
    {
        $sql = "UPDATE clients SET nom = ? WHERE idClient =  ?";
        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$this->nom, $this->idClient]);
    }


    //! METHODS

    //TODO À CHECK
    public function verifClient($nomClient)
    {
        $sql = "SELECT * FROM clients WHERE nom = ?";
        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$nomClient]);

        if($requete->fetch(PDO::FETCH_ASSOC) > 0)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

}
?>
