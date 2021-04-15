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
    
    public function recupererMaxMinIdEquipes($idOrganisation)
    {
        $requete = $this->getBdd()->prepare("SELECT max(idEquipe) as MaxId, min(idEquipe) as MinId FROM equipes WHERE idOrganisation = ?");
        $requete->execute([$idOrganisation]);
        return $requete->fetch(PDO::FETCH_ASSOC);
    }
    
    public function recupererNombreMembreParEquipe($idOrganisation)
    {
        $requete = $this->getBdd()->prepare("SELECT idEquipe, count(utilisateurs.idEquipe) as UtilisateursParEquipe FROM equipes left join utilisateurs using(idEquipe) where equipes.idOrganisation = ? group by equipes.idEquipe");
        $requete->execute([$idOrganisation]);
        return $requete->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function recupererNombreMembreEquipe($idOrganisation, $idEquipe)
    {
        $requete = $this->getBdd()->prepare("SELECT idEquipe, count(utilisateurs.idEquipe) as UtilisateursEquipe FROM equipes left join utilisateurs using(idEquipe) where equipes.idOrganisation = ? and idEquipe = ?");
        $requete->execute([$idOrganisation, $idEquipe]);
        return $requete->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function ajouterEquipe($ajoutEquipe, $idOrganisation)
    {
        $requete = $this->getBdd()->prepare("INSERT INTO equipes (nomEquipe, idOrganisation) VALUES (?,?)");
        $requete->execute([$ajoutEquipe, $idOrganisation]);
    }
    
    public function recupChefEquipe($idEquipe)
    {
        $requete = $this->getBdd()->prepare("SELECT utilisateurs.nom, utilisateurs.prenom, utilisateurs.email FROM equipes INNER JOIN utilisateurs ON utilisateurs.idUtilisateur = equipes.chefEquipe WHERE equipes.idEquipe = ?");
        $requete->execute([$idEquipe]);
        return $requete->fetch(PDO::FETCH_ASSOC);
    }
    
    public function recupChefEquipesParOrganisation($idOrganisation)
    {
        $requete = $this->getBdd()->prepare("SELECT utilisateurs.nom, utilisateurs.prenom, utilisateurs.email, chefEquipe, utilisateurs.idUtilisateur FROM equipes LEFT JOIN utilisateurs ON utilisateurs.idUtilisateur = equipes.chefEquipe WHERE equipes.idOrganisation = ?");
        $requete->execute([$idOrganisation]);
        return $requete->fetchAll(PDO::FETCH_ASSOC);
    }
}