<?php require_once "entete.php";
?>

<div class="col-10 mt-4 w-75">

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

                <a class="btn btn-danger" href="gestionOrganisation.php?action=deleteOrganisation">Oui</a>

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
        </div>
    </div>

<script src="js/gestionOrganisation.php"></script>

<?php
require_once "pied.php";
