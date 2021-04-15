<?php require_once 'header.php';

if($_SESSION['habilitation'] == 'admin')
{
    if(!empty($_POST['envoi']) || !empty($_GET['idProjet']))
    {
        extract($_GET);
        extract($_POST);
        if(!empty($titre) && !empty($type) && !empty($description) && !empty($client) && !empty($chefProjet) && !empty($equipesAjoutees))
        {
            // si client n'existe pas insert into clients
            $nomPrenomChefProjet = explode(" " , $chefProjet);
            $prenomChef = $nomPrenomChefProjet[0];
            $nomChef = $nomPrenomChefProjet[1];
            $idChefProjet = recupIdChefProjet($nomChef, $prenomChef);
            $idClient = recupIdClient($client);
            // print($idChefProjet);
            // print($nomChef);
            // print('<br>');
            // print($prenomChef);
            // exit;
            if(verifClient($client) == true)
            {
                // le client existe dans la bdd
                try {
                    creerProjet($titre, $type, $deadline, $idClient['idClient'], $idChefProjet['idUtilisateur']);
                    for($i = 0; $i < strlen($equipesProjet); $i++ )
                    {
                        addEquipesProjet($idProjet, $equipesAjoutees[$i]);
                    }
                    header("location:../admin/creationProjets.php?success=1");
                } catch (exception $e) {
                    header("location:../admin/creationProjets.php?error=fatalError&idProjet=$idProjet");
                }
            } else {
                // le client n'existe pas dans la bdd
                try {
                    insertClient($client);
                    creerProjet($titre, $type, $deadline, $idClient['idClient'], $idChefProjet['idUtilisateur']);
                    print_r($equipesAjoutees);
                    exit;
                    for($i = 0; $i < strlen($equipesProjet); $i++ )
                    {
                        addEquipesProjet($idProjet, $equipesAjoutees[$i]);
                    }
                    header("location:../admin/creationProjets.php?success=1");
                } catch (exception $e) {
                    echo '<div class="alert alert-danger">';
                    echo $e->getMessage();
                    echo "</div>";
                    exit;
                    header("location:../admin/creationProjets.php?error=fatalError&idProjet=$idProjet");
                }
            }
        } else {
            header("location:../admin/creationProjets.php?error=champsVide&idProjet=$idProjet");
        }
    } else {
        header('location:../index.php');
    }
} else {
    header('location:../index.php');
}
?>