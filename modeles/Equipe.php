<?php
class Equipe extends Modele
{
    private $idEquipe;
    private $nomEquipe;
    private $idOrganisation;
    private $chefEquipe;
    private $membres = [];

    public function __construct($idE = null)
    {
        if($idE != null)
        {
            $requete = $this->getBdd()->prepare("SELECT * FROM equipes WHERE idEquipe = ?");
            $requete->execute([$idE]);
            $equipe = $requete->fetch(PDO::FETCH_ASSOC);
            $this->idEquipe = $idE;
            $this->nomEquipe = $equipe["nomEquipe"];
            $this->idOrganisation = $equipe["idOrganisation"];
            $this->chefEquipe = $equipe["chefEquipe"];

            $requete = $this->getBdd()->prepare("SELECT * FROM utilisateurs WHERE idEquipe = ?");
            $requete->execute([$this->idEquipe]);
            $membres = $requete->fetchAll(PDO::FETCH_ASSOC);
            foreach($membres as $membre)
            {
                $ObjetMembre = new Utilisateur($membre["idUtilisateur"]);
                $this->membres[] = $ObjetMembre;
            }
        }
    }

    // SETTER
    public function setNomEquipe($nE)
    {
        $this->nomEquipe = $nE;
        $requete = $this->getBdd()->prepare("UPDATE equipes SET nomEquipe = ? WHERE idEquipe =  ?");
        $requete->execute([$this->nomEquipe, $this->idEquipe]);
    }

    public function setChefEquipe($cE)
    {
        $this->chefEquipe = $cE;
        $requete = $this->getBdd()->prepare("UPDATE equipes SET chefEquipe = ? WHERE idEquipe =  ?");
        $requete->execute([$this->chefEquipe, $this->idEquipe]);
    }

    // GETTER
    public function getNomEquipe()
    {
        return $this->nomEquipe;
    }

    public function getIdEquipe()
    {
        return $this->idEquipe;
    }

    public function getChefEquipe()
    {
        return $this->chefEquipe;
    }

    public function getIdOrganisation()
    {
        return $this->idOrganisation;
    }

    public function getMembresEquipe()
    {
        return $this->membres;
    }
    
    // METHODES

    public function recupererNombreMembreEquipe($idOrganisation, $idEquipe)
    {
        $requete = $this->getBdd()->prepare("SELECT idEquipe, count(utilisateurs.idEquipe) as UtilisateursEquipe FROM equipes left join utilisateurs using(idEquipe) where equipes.idOrganisation = ? and idEquipe = ?");
        $requete->execute([$idOrganisation, $idEquipe]);
        return $requete->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function recupChefEquipe($idEquipe)
    {
        $requete = $this->getBdd()->prepare("SELECT utilisateurs.nom, utilisateurs.prenom, utilisateurs.email FROM equipes INNER JOIN utilisateurs ON utilisateurs.idUtilisateur = equipes.chefEquipe WHERE equipes.idEquipe = ?");
        $requete->execute([$idEquipe]);
        return $requete->fetch(PDO::FETCH_ASSOC);
    }
    

}