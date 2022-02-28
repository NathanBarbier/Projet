<?php
//import all models
require_once "../../services/header.php";
require "layouts/head.php";

$User = new User($idUser);

$tpl = 'index.php';


require_once VIEWS_PATH.'admin/'.$tpl;
?>