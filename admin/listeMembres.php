<?php
require_once "entete.php";
// Récuperer toutes les infos de chaque utilisateurs d'une organisation
$membres = recupererInfoUtilisateurs($_SESSION["idOrganisation"]);
$postes = recupererPostes($_SESSION["idOrganisation"]);
$equipes = recupererEquipes($_SESSION["idOrganisation"]);

if(!empty($_GET["success"]))
{
    ?>
    <div class="alert alert-success">
    <?php
    switch ($_GET["success"]) {
        case "modifierEquipe":
            echo "L'équipe de l'utilisateur a bien été modifiée.";
            break;
        case "modifierPoste":
            echo "Le poste de l'utilisateur a bien été modifié.";
            break;
        case "modifierNom":
            echo "Le nom de l'utilisateur a bien été modifié.";
            break;
        case "modifierPrenom":
            echo "Le prénom de l'utilisateur a bien été modifié.";
            break;
    }
    ?>
    </div>
    <?php
} else if(!empty($_GET["error"]))
{
    ?>
    <div class="alert alert-danger">
    <?php
    switch ($_GET["error"]) { 
        case "modifEquipeFatalError":
            echo "Erreur : La modification d'équipe n'a pas pu aboutir.";
            break;
        case "modifPosteFatalError":
            echo "Erreur : La modification de poste n'a pas pu aboutir.";
            break;
        case "modifNomFatalError":
            echo "Erreur : La modification du nom n'a pas pu aboutir.";
            break;
        case "nameNoChange":
            echo "Erreur : Vous n'avez pas modifié le nom";
            break;
        case "modifPrenomFatalError":
            echo "Erreur : La modification du prenom n'a pas pu aboutir.";
            break;
        case "surnameNoChange":
            echo "Erreur : Vous n'avez pas modifié le prenom";
            break;
    }
    ?>
    </div>
    <?php
}
?>
<div class="col-10">

<table class="table">
    <thead>
        <tr>
            <th colspan="7">
                <div style="float : left"><strong>Liste des membres</strong></div>
                <div style="float : right">
                    <div>Nombres de membres : <?=count($membres);?></div> 
                </div>
            </th>
        </tr>
    </thead>
    <tbody>
        <tr class="text-center">
            <th>NOM</th>
            <th>Prénom</th>
            <th>Équipe</th>
            <th>Poste</th>
            <th>Adresse email</th>
            <th>Infos</th>
            <th>Options</th>
        </tr>

        <?php
        foreach($membres as $membre)
        {
        ?>
        <tr class="text-center">
            <td>
                <div id="nom<?= $membre['idUtilisateur'] ?>" class="collapse show">
                    <?=$membre["nom"];?>
                </div>

                <div id="divModifNom<?= $membre['idUtilisateur'] ?>" class="collapse show">
                    <a onclick="afficherConfModifNom(<?= $membre['idUtilisateur'] ?>)" class="btn btn-outline-info">Modifier</a>
                </div>
                <form method="POST" action="../traitements/modifierNomUtilisateur.php?idUser=<?= $membre['idUtilisateur'] ?>">
                    <div id="divInputModifNom<?= $membre['idUtilisateur'] ?>" class="collapse">
                        <input class="form-control mb-1 text-center" value="<?= $membre['nom'] ?>" type="text" name="nom" placeholder="Écrivez un nom" required>
                    </div>

                    <!-- Confirmer la modification du nom de l'utilisateur -->
                    <div id="divConfModifNom<?= $membre['idUtilisateur'] ?>" class="collapse">
                        <button type="submit" name="envoi" value="<?= true ?>" class="btn btn-success">Confirmer</button>
                        <a onclick="annulerModifNom(<?= $membre['idUtilisateur'] ?>)" class="btn btn-warning">Annuler</a>
                    </div>
                </form>
            </td>
            
            <td>
                <div id="prenom<?= $membre['idUtilisateur'] ?>" class="collapse show">
                    <?=$membre["prenom"];?>
                </div>

                <div id="divModifPrenom<?= $membre['idUtilisateur'] ?>" class="collapse show">
                    <a onclick="afficherConfModifPrenom(<?= $membre['idUtilisateur'] ?>)" class="btn btn-outline-info">Modifier</a>
                </div>
                <form method="POST" action="../traitements/modifierPrenom.php?idUser=<?= $membre['idUtilisateur'] ?>">
                    <div id="divInputModifPrenom<?= $membre['idUtilisateur'] ?>" class="collapse">
                        <input class="form-control mb-1 text-center" value="<?= $membre['prenom'] ?>" type="text" name="prenom" placeholder="Écrivez un prénom" required>
                    </div>

                    <!-- Confirmer la modification du nom de l'utilisateur -->
                    <div id="divConfModifPrenom<?= $membre['idUtilisateur'] ?>" class="collapse">
                        <button type="submit" name="envoi" value="<?= true ?>" class="btn btn-success">Confirmer</button>
                        <a onclick="annulerModifPrenom(<?= $membre['idUtilisateur'] ?>)" class="btn btn-warning">Annuler</a>
                    </div>
                </form>
            </td>

            <td>
                <!-- On affiche l'équipe de l'utilisateur -->
                <div id="divNomEquipe<?= $membre['idUtilisateur'] ?>" class="collapse show">
                    <?=$membre["nomEquipe"];?>
                </div>
                <!-- On affiche toutes les équipe de l'organisation -->
                <form method="post" action="../traitements/modifierEquipe.php?idUser=<?= $membre["idUtilisateur"] ?>">
                    <div id="divSelectEquipes<?= $membre['idUtilisateur'] ?>" class="collapse text-center">
                        <select name="idEquipe" class="text-center form-control w-75 mx-auto mb-1" required>
                            <?php foreach($equipes as $equipe)
                            { ?>
                                <option value="<?= $equipe['idEquipe'] ?>"><?= $equipe["nomEquipe"] ?></option>
                            <?php } ?>
                        </select>
                    </div>

                    <!-- On affiche le bouton modifier l'équipe -->
                    <div id="divModifEquipe<?= $membre["idUtilisateur"] ?>" class="collapse show ml-auto">
                        <a onclick="afficherConfModifEquipe(<?= $membre['idUtilisateur'] ?>)" class="btn btn-outline-info">Modifier</a>
                    </div>
                    <!-- On affiche la div de confirmation de modification d'équipe -->
                    <div id="divConfModifEquipe<?= $membre["idUtilisateur"] ?>" class="collapse">
                        <button type="submit" class="btn btn-outline-success">Confirmer</button><br>

                        <a onclick="annulerModifEquipe(<?= $membre['idUtilisateur'] ?>)" class="btn btn-outline-danger mt-1">Annuler</a>
                    </div>
                </form>
            </td>

            <td>
                <div id="divNomPoste<?= $membre['idUtilisateur'] ?>" class="collapse show">
                    <?=$membre["nomPoste"];?>
                </div>
                <!-- On affiche tous les postes de l'organisation -->
                <form method="POST" action="../traitements/modifierPoste.php?idUser=<?= $membre['idUtilisateur'] ?>">
                    <div id="divSelectPostes<?= $membre['idUtilisateur'] ?>" class="collapse text-center">
                        <select name="idPoste" class="text-center form-control w-75 mx-auto mb-1">
                            <?php foreach($postes as $poste)
                            { ?>
                                <option value="<?= $poste['idPoste'] ?>"><?= $poste["nomPoste"] ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div id="divModifPoste<?= $membre["idUtilisateur"] ?>" class="collapse show">
                        <a onclick="afficherConfModifPoste(<?= $membre['idUtilisateur'] ?>)" class="btn btn-outline-info">Modifier</a>
                    </div>
                    <div id="divConfModifPoste<?= $membre["idUtilisateur"] ?>" class="collapse">
                        <button type="submit" class="btn btn-outline-success">Confirmer</button><br>

                        <a onclick="annulerModifPoste(<?= $membre['idUtilisateur'] ?>)" class="btn btn-outline-danger mt-1">Annuler</a>
                    </div>
                </form>
            </td>

            <td><?=$membre["email"];?></td>

            <td>
                <a href="infoMembre.php?id=<?=$membre["idUtilisateur"];?>" class="btn btn-outline-primary">Fiches </a>
            </td>
            <td>
                <div id="divDelUser<?= $membre["idUtilisateur"] ?>" class="collapse show">
                    <button onclick="afficherConfDelUser(<?= $membre['idUtilisateur'] ?>)" class="btn btn-outline-danger">Supprimer</button>
                </div>
                <div id="divConfDelUser<?= $membre["idUtilisateur"] ?>" class="collapse">
                    <div>
                        <div class="col-6">
                            <a href="../traitements/supprimerUtilisateur/php?idUser=<?= $membre["idUtilisateur"] ?>" class="btn btn-outline-danger">Confirmer</a><br>
                        </div>
                        <div class="col-6 mt-2">
                            <button onclick="annulerDelUser(<?= $membre['idUtilisateur'] ?>)" class="btn btn-outline-warning">Annuler</button>
                        </div>
                    </div class="row">
                </div>
            </td>
        </tr>
        <?php
        }

        ?>
    </tbody>

</table>

<script>
    // Modification du nom de l'utilisateur
        function afficherConfModifNom($idUser)
        {
            var cacher = "nom" + $idUser;
            var cacher2 = "divModifNom" + $idUser;
            var afficher = "divConfModifNom" + $idUser;
            var afficher2 = "divInputModifNom" + $idUser;

            document.getElementById(cacher).classList.remove("show");
            document.getElementById(cacher2).classList.remove("show");
            document.getElementById(afficher).classList.add("show");
            document.getElementById(afficher2).classList.add("show");
        }

        function annulerModifNom($idUser)
        {
            var cacher = "divConfModifNom" + $idUser;  
            var cacher2 = "divInputModifNom" + $idUser;  
            var afficher = "nom" + $idUser;
            var afficher2 = "divModifNom" + $idUser;

            document.getElementById(afficher).classList.add("show");
            document.getElementById(afficher2).classList.add("show");
            document.getElementById(cacher).classList.remove("show");
            document.getElementById(cacher2).classList.remove("show");2
        }
    //Modification du prenom de l'utilisateur
    function afficherConfModifPrenom($idUser)
        {
            var cacher = "prenom" + $idUser;
            var cacher2 = "divModifPrenom" + $idUser;
            var afficher = "divConfModifPrenom" + $idUser;
            var afficher2 = "divInputModifPrenom" + $idUser;

            document.getElementById(cacher).classList.remove("show");
            document.getElementById(cacher2).classList.remove("show");
            document.getElementById(afficher).classList.add("show");
            document.getElementById(afficher2).classList.add("show");
        }

        function annulerModifPrenom($idUser)
        {
            var cacher = "divConfModifPrenom" + $idUser;  
            var cacher2 = "divInputModifPrenom" + $idUser ;  
            var afficher = "prenom" + $idUser;
            var afficher2 = "divModifPrenom" + $idUser;

            document.getElementById(afficher).classList.add("show");
            document.getElementById(afficher2).classList.add("show");
            document.getElementById(cacher).classList.remove("show");
            document.getElementById(cacher2).classList.remove("show");2
        }

    // Suppression de l'utilisateur
        function afficherConfDelUser($idUser)
        {
            var cacher = "divDelUser" + $idUser;
            var afficher = "divConfDelUser" + $idUser;
            document.getElementById(cacher).classList.remove("show");
            document.getElementById(afficher).classList.add("show");
        }

        function annulerDelUser($idUser)
        {
            var cacher = "divConfDelUser" + $idUser  ;  
            var afficher = "divDelUser" + $idUser;
            document.getElementById(afficher).classList.add("show");
            document.getElementById(cacher).classList.remove("show");
        }

    // Modification de l'équipe de l'utilisateur
        function afficherConfModifEquipe($idUser)
        {
            var cacher = "divModifEquipe" + $idUser;
            var cacher2 = "divNomEquipe" + $idUser;
            var afficher = "divConfModifEquipe" + $idUser;
            var afficher2 = "divSelectEquipes" + $idUser;

            document.getElementById(cacher).classList.remove("show");
            document.getElementById(cacher2).classList.remove("show");
            document.getElementById(afficher).classList.add("show");
            document.getElementById(afficher2).classList.add("show");
        }

        function annulerModifEquipe($idUser)
        {
            var cacher = "divConfModifEquipe" + $idUser;
            var cacher2 = "divSelectEquipes" + $idUser;
            var afficher = "divModifEquipe" + $idUser;
            var afficher2 = "divNomEquipe" + $idUser;
            
            document.getElementById(afficher).classList.add("show");
            document.getElementById(afficher2).classList.add("show");
            document.getElementById(cacher).classList.remove("show");
            document.getElementById(cacher2).classList.remove("show");
        }

    // Modification du poste de l'utilisateur
        function afficherConfModifPoste($idUser)
        {
            var cacher = "divModifPoste" + $idUser;
            var cacher2 = "divNomPoste" + $idUser;
            var afficher = "divConfModifPoste" + $idUser;
            var afficher2 = "divSelectPostes" + $idUser;

            document.getElementById(cacher).classList.remove("show");
            document.getElementById(cacher2).classList.remove("show");
            document.getElementById(afficher).classList.add("show");
            document.getElementById(afficher2).classList.add("show");
        }

        function annulerModifPoste($idUser)
        {
            var cacher = "divConfModifPoste" + $idUser;
            var cacher2 = "divSelectPostes" + $idUser;
            var afficher = "divModifPoste" + $idUser;
            var afficher2 = "divNomPoste" + $idUser;

            document.getElementById(cacher).classList.remove("show");
            document.getElementById(cacher2).classList.remove("show");
            document.getElementById(afficher).classList.add("show");
            document.getElementById(afficher2).classList.add("show");
        }


    // function afficherDiv()
    // {
    //     this.parentElement.classList.remove("show");
    //     this.parentElement.parentElement.children[1].classList.add("show");
    // }

    // function cacherDiv()
    // {
    //     this.parentElement.classList.remove("show");
    //     this.parentElement.parentElement.children[0].classList.add("show");
    // }
</script>


<?php
require_once "pied.php";
?>