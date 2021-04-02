<?php
function getBdd()
{
    // INITIALISATION DE LA CONNEXION A LA BDD
    return new PDO('mysql:host=localhost;dbname=projet;charset=UTF8', 'root');
}

function generateRandomString($length = 10) {
    return substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
}

require_once '../modeles/clients.php';
require_once '../modeles/equipes.php';
require_once '../modeles/organisations.php';
require_once '../modeles/postes.php';
require_once '../modeles/projets.php';
require_once '../modeles/utilisateurs.php';
require_once '../modeles/roles.php';
?>