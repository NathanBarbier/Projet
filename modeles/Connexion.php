<?php
class Connexion extends Modele
{
    public function recupUtilisateurMail($mail)
    {
        $sql = "SELECT * FROM utilisateurs WHERE email = ?";
        $requete = $this->getBdd()->prepare($sql);
        $requete->execute([$mail]);
        return $requete->fetch(PDO::FETCH_ASSOC);
    }
}


?>