<?php require_once "entete.php";

$InfosOrganisation = recupererInfoOrganisation($_SESSION["idOrganisation"]);
<<<<<<< HEAD
=======
// print_r($InfosOrganisation);
>>>>>>> 8fdd04aa91654970e469b2aa8e2258f03bb110a9

if(!empty($_GET["Suppression"]) && $_GET["Suppression"] === $_SESSION["idOrganisation"])
{
    supprimerOrganisation();
}
?>
<<<<<<< HEAD
<div class="col-10 mt-4 w-75">
=======
<div class="col-10 mt-4">
>>>>>>> 8fdd04aa91654970e469b2aa8e2258f03bb110a9

        <div class="row">
            <div class="col">
            <!-- onclick développer le ruban de confirmation de suppression pour éviter un reload inutile -->
                <button id="boutonDelOrg" class="btn btn-primary" >Supprimer l'organisation</button>
            </div>

            <div class="col">
                <a id="boutonOption2" class="btn btn-primary">Option 2</a >
            </div>

            <div class="col">
                <a id="boutonOption3" class="btn btn-primary">Option 3</a >
            </div>
        </div>

    <div class="row mt-4">
        <div class="col-7">
            <div class="mt-3" id="divDelOrg">
                Êtes-vous sûr de vouloir supprimer l'organisation ?<br>
                (Cette action est définitive et supprimer toute donnée étant en lien avec l'organisation)

                <a class="btn btn-danger" href="gestionOrganisation.php?Suppression=<?=$_SESSION["idOrganisation"]?>">Oui</a>

                <button id="boutonRefusDel" class="btn btn-warning">Non</button>
            </div>

            <div class="mt-3" id="divOption2">
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Delectus voluptatum eius laborum cupiditate! Quis unde id harum voluptatem facilis debitis explicabo reiciendis ipsam sit sed. Ut dolore deserunt corporis explicabo?</p>
                <br>
                <button id="boutonCacheOption2" class="btn btn-warning">Cacher Option 2</button>
            </div>

            <div class="mt-3" id="divOption3">
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Delectus voluptatum eius laborum cupiditate! Quis unde id harum voluptatem facilis debitis explicabo reiciendis ipsam sit sed. Ut dolore deserunt corporis explicabo?</p>
                <br>
                <button id="boutonCacheOption3" class="btn btn-warning">Cacher Option 3</button>
            </div>
        </div>

<<<<<<< HEAD
    <div class="col-4" >
        <div class="card">
            <div class="card-header">
                <h4>Informations sur l'organisation</h4>
            </div>
            <div class="card-body">
                <p><b><?= $InfosOrganisation["nom"] ?></b></p><br>
                <p>Email Admin : <?= $InfosOrganisation["email"] ?></p><br>
                <p>Nombre d'employés : <?= $InfosOrganisation["nombreEmployes"] ?></p><br>
                <p>Nombre d'équipes : <?= $InfosOrganisation["nombreEquipes"] ?></p>
=======
        <div class="col-4" >
            <div class="card">
                <div class="card-header">
                    <h4>Informations sur l'organisation</h4>
                </div>
                <div class="card-body">
                    <p><b><?= $InfosOrganisation["nom"] ?></b></p><br>
                    <p>Email Admin : <?= $InfosOrganisation["email"] ?></p><br>
                    <p>Nombre d'employés : <?= $InfosOrganisation["nombreEmployes"] ?></p><br>
                    <p>Nombre d'équipes : <?= $InfosOrganisation["nombreEquipes"] ?></p>
                </div>
>>>>>>> 8fdd04aa91654970e469b2aa8e2258f03bb110a9
            </div>
        </div>
    </div>

<script>

    // On cache les div de chaque option
    divDelOrg.style.visibility = "collapse";
    divDelOrg.classList.add("collapse");
    divDelOrg.style.opacity = "0%";

    divOption2.style.visibility = "collapse";
    divOption2.classList.add("collapse");
    divOption2.style.opacity = "0%";

    divOption3.style.visibility = "collapse";
    divOption3.classList.add("collapse");
    divOption3.style.opacity = "0%";

    // On ajoute la fonction d'affichage aux boutons d'options
  
    boutonDelOrg.addEventListener("click", function(){afficherOption(divDelOrg, boutonDelOrg)});
    boutonOption2.addEventListener("click", function(){afficherOption(divOption2, boutonOption2)});
    boutonOption3.addEventListener("click", function(){afficherOption(divOption3, boutonOption3)});

    // On ajoute la fonction masquage au bouton "non" de l'option Supprimer Organisation

    boutonRefusDel.addEventListener("click", function(){cacherOption(divDelOrg, boutonDelOrg)});
    boutonCacheOption2.addEventListener("click", function(){cacherOption(divOption2, boutonOption2)});
    boutonCacheOption3.addEventListener("click", function(){cacherOption(divOption3, boutonOption3)});

    // On modifie la durée de transition de l'opacité de chaque bouton d'option
    boutonDelOrg.style.transition = "opacity 1s ease-in-out";
    boutonOption2.style.transition = "opacity 1s ease-in-out";
    boutonOption3.style.transition = "opacity 1s ease-in-out";


    function afficherOption(divOption, boutonOption) 
    {
        boutonOption.removeEventListener("click", afficherOption);

        divOption.style.visibility = "visible";
        divOption.classList.remove("collapse");
        divOption.style.transition = "opacity 1s ease-in-out";
        divOption.style.opacity = "100%";
        boutonOption.style.opacity = "0%";

    }

    function cacherOption(divOption, boutonOption)
    {
        divOption.style.opacity = "0%";
        boutonOption.style.visibility = "visible";
        boutonOption.style.transition = "opacity 1s ease-in-out";
        boutonOption.style.opacity = "100%";
        boutonOption.classList.add("disabled")
        setTimeout(function(){
            
            boutonOption.addEventListener("click", afficherOption );
            boutonOption.classList.remove("disabled");
            divOption.style.visibility = "collapse";
            divOption.classList.add("collapse");

        }, 1000)

    }
</script>

<?php
require_once "pied.php";
