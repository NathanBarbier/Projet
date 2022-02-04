<?php
//import all models
require_once "../../services/header.php";
require "layouts/head.php";

$idOrganization = $_SESSION["idOrganization"] ?? false;
$rights = $_SESSION["rights"] ?? false;
$idUser = $_SESSION["idUser"] ?? false;

$tpl = "listeProjets.php";

$Organization = new Organization($idOrganization);

require_once VIEWS_PATH."admin/".$tpl;
