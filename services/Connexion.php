<?php
class Connexion extends Modele
{
    public function fetchUserByEmail($email)
    {
        $sql = "SELECT *"; 
        $sql .= " FROM users";
        $sql .= " WHERE email = ?";
        
        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$email]);
        
        return $requete->fetch(PDO::FETCH_OBJ);
    }
}


?>