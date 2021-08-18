<?php

Class Role extends Modele 
{
    private $id;
    private $nom;

    public function __construct($id = null)
    {
        if($id !== null)
        {
            $sql = "SELECT * FROM roles WHERE idRole = ?";
            $requete = $this->getBdd()->prepare($sql);
            $requete->execute([$id]);

            $role = $requete->fetch(PDO::FETCH_ASSOC);

            $this->id = $id;
            $this->nom = $role["nom"];
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


    //! FETCH

    public function fetchAll()
    {
        $sql = "SELECT * FROM roles";
        $requete = $this->getBdd()->prepare($sql);
        $requete->execute();

        return $requete->fetchAll(PDO::FETCH_ASSOC);
    }
}