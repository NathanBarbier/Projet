<?php
//import all models
require_once "../../services/header.php";
require "layouts/head.php";

$action     = htmlentities(GETPOST('action'));
$idUser     = intval(htmlentities(GETPOST('idUser')));

$firstname  = htmlentities(GETPOST('firstname'));
$lastname   = htmlentities(GETPOST('lastname'));
$email      = htmlentities(GETPOST('email'));
$birth      = htmlentities(GETPOST('birth'));

$oldmdp     = htmlentities(GETPOST('oldmdp'));
$newmdp     = htmlentities(GETPOST('newmdp'));
$newmdp2    = htmlentities(GETPOST('newmdp2'));

$Organization = new Organization();
$Organization->setRowid($idOrganization);
$Organization->fetchUsers();

$errors = array();
$success = false;

// for pagination
$offset = 30;

$tpl = "listeMembres.php";

if($action == "userUpdate")
{
    if($idUser)
    {
        // check if the user belongs to the Organization
        $User = $Organization->fetchUser($idUser);
        if($User)
        {
            if(!empty($firstname)) {
                $User->setFirstname($firstname);
            }
            if(!empty($lastname)) {
                $User->setLastname($lastname);
            }
            if(!empty($email)) {
                $User->setEmail($email);
            }

            try {
                $User->update();
                
                LogHistory::create($idOrganization, $idUser, "INFO", 'update firstname', 'user', '', 'user id : '.$User->getRowid());
                
                $success = "L'utilisateur a bien été mis à jour.";
            } catch (exception $e) {
                $errors[] = "Le prénom n'a pas pu être modifié.";
                LogHistory::create($idOrganization, $idUser, "ERROR", 'update firstname', 'user', '', 'user id : '.$User->getRowid(), $th);
            }
        }
    } 
    else 
    {
        header("location:".ROOT_URL."/index.php");
    }
}

if($action == "userDelete")
{
    if($idUser)
    {
        // check if the user belongs to the organization
        $User = $Organization->fetchUser($idUser);
        if($User)
        {
            try {
                $Organization->removeUser($idUser);
                $User->delete();
                LogHistory::create($idOrganization, $idUser, "WARNING", 'delete', 'user', $User->getLastname().' '.$User->getFirstname(), '', 'user id : '.$User->getRowid());
                $success = "La suppression d'utilisateur a bien été effectuée.";
            } catch (\Throwable $th) {
                $errors[] = "La suppression d'utilisateur n'a pas pu aboutir.";
                LogHistory::create($idOrganization, $idUser, "ERROR", 'delete', 'user', $User->getLastname().' '.$User->getFirstname(), '', 'user id : '.$User->getRowid(), $th);
            }
        }
    } 
    else
    {
        header("location:".ROOT_PATH."index.php");
    }
}

?>
<script>
var offset = <?php echo json_encode($offset); ?>;
</script>
<?php

require_once VIEWS_PATH."admin/".$tpl;
