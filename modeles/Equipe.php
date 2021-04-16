<?php
class Equipe extends Modele
{
    private $idEquipe;
    private $nomEquipe;
    private $idOrganisation;
    private $chefEquipe;

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
        }
    }

    // SETTER
    public function setNomEquipe($nE)
    {
        $this->nomEquipe = $nE;
    }

    public function setChefEquipe($cE)
    {
        $this->chefEquipe = $cE;
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