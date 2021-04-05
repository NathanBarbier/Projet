<?php require_once 'header.php';

if($_SESSION['habilitation'] == 'admin')
{
    if(!empty($_POST['envoi']) || !empty($_GET['idProjet']))
    {
        extract($_GET);
        extract($_POST);
        if(!empty($titre) && !empty($type) && !empty($deadline) && !empty($description) && !empty($client) && !empty($chefProjet))
        {
            // si client n'existe pas insert into clients
            explode(" " , $chefProjet);
            $nomChef = $chefProjet[0];
            $prenomChef = $chefProjet[1];
            $idChefProjet = recupIdChefProjet($nomChef, $prenomChef);
            $idClient = recupIdClient($client);
            if(verifClient($client) == true)
            {
                // le client existe dans la bdd
                try {
                    creerProjet($titre, $type, $deadline, $idClient, $idChefProjet);
                    addEquipesProjet();
                    header("location:../admin/creationProjet.php?success=1");
                } catch (exception $e) {
                    header("location:../admin/creationProjet.php?error=fatalError&idProjet=$idProjet");
                }
            } else {
                // le client n'existe pas dans la bdd
                try {
                    insertClient($client);
                    creerProjet($titre, $type, $deadline, $idClient, $idChefProjet);
                    header("location:../admin/creationProjets.php?success=1");
                } catch (exception $e) {
                    header("location:../admin/creationProjet.php?error=fatalError&idProjet=$idProjet");
                }
            }
        } else {
            header("location:../admin/creationProjet.php?error=champsVide&idProjet=$idProjet");
        }
    } else {
        header('location:../index.php');
    }
} else {
    header('location:../index.php');
}
?>