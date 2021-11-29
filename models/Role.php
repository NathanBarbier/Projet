<?php

Class Role extends Modele 
{
    private $id;
    private $name;

    public function __construct($id = null)
    {
        if($id !== null)
        {
            $sql = "SELECT *";
            $sql .= " FROM roles"; 
            $sql .= " WHERE rowid = ?";

            $requete = $this->getBdd()->prepare($sql);
            $requete->execute([$id]);

            $role = $requete->fetch(PDO::FETCH_OBJ);

            $this->id = $id;
            $this->name = $role->name;
        }
    }


    //! GETTER

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }


    //! FETCH

    public function fetchAll()
    {
        $sql = "SELECT r.rowid, r.name ";
        $sql .= " FROM roles AS r";
        
        $requete = $this->getBdd()->prepare($sql);
        $requete->execute();

        return $requete->fetchAll(PDO::FETCH_OBJ);
    }
}