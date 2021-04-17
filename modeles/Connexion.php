<?php
class Connexion extends Modele
{
    public function recupUtilisateurMail($mail)
        {
            $requete = $this->getBdd()->prepare("SELECT * FROM utilisateurs WHERE email = ?");
            $requete->execute([$mail]);
            return $requete->fetch(PDO::FETCH_ASSOC);
        }
}


?>