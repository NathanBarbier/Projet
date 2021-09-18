<?php
require_once "../../traitements/header.php";

$tpl = "infoMembres.php";

$data = new stdClass;

$data = array(

);

$data = json_encode($data);

header("location:".VIEWS_URL."admin/".$tpl."?data=$data");

?>