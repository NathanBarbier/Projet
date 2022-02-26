<?php
//import all models
require_once "../../services/header.php";
// require "layouts/head.php";

// var_dump($_POST, $_GET, $_SESSION);

$tpl = 'index.php';


require_once VIEWS_PATH.'admin/'.$tpl;
?>