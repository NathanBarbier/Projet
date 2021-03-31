<?php
require_once "entete.php";

$equipes = recupererEquipes($_SESSION["idOrganisation"]);
$nbMembresEquipes = recupererNombreMembreParEquipe($_SESSION["idOrganisation"]);
?>
<div class="col-10">
<div class="container mt-3">
    
</div>

