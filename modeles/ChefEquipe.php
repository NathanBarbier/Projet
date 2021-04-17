<?php 

Class chefProjet extends Utilisateur
{

    public function addProjet($nom,$type,$dateRendu,$idClient,$etat,$chefProjet)
    {
        $requete = $this->getBdd()->prepare("INSERT INTO projets(nom,type,DateDebut,DateRendu,idClient,etat,chefProjet) VALUES (?,?,NOW(),?,?,?,?");
        $requete->execute([$nom,$type,$dateRendu,$idClient,$etat,$chefProjet]);
    }

    public function addTache($titre,$description,$taille,$etat,$idProjet)
    {
        $requete = $this->getBdd()->prepare("INSERT INTO taches (titre,description, taille,etat,idProjet) VALUES (?,?,?,?,?)");
        $requete->execute([$titre,$description,$taille,$etat,$idProjet]);
    }

}