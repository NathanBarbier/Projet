<?php
//import all models
require_once "../../services/header.php";
require "layouts/head.php";

$action     = htmlspecialchars(GETPOST('action'), ENT_NOQUOTES|ENT_SUBSTITUTE, "UTF-8");
$userId     = intval(htmlspecialchars(GETPOST('idUser')));

$firstname  = htmlspecialchars(GETPOST('firstname'), ENT_NOQUOTES|ENT_SUBSTITUTE, "UTF-8");
$lastname   = htmlspecialchars(GETPOST('lastname'), ENT_NOQUOTES|ENT_SUBSTITUTE, "UTF-8");
$email      = htmlspecialchars(GETPOST('email'), ENT_NOQUOTES|ENT_SUBSTITUTE, "UTF-8");
$birth      = htmlspecialchars(GETPOST('birth'), ENT_NOQUOTES|ENT_SUBSTITUTE, "UTF-8");
$role       = intval(htmlspecialchars(GETPOST('role')));

$oldmdp     = htmlspecialchars(GETPOST('oldmdp'), ENT_NOQUOTES|ENT_SUBSTITUTE, "UTF-8");
$newmdp     = htmlspecialchars(GETPOST('newmdp'), ENT_NOQUOTES|ENT_SUBSTITUTE, "UTF-8");
$newmdp2    = htmlspecialchars(GETPOST('newmdp2'), ENT_NOQUOTES|ENT_SUBSTITUTE, "UTF-8");

$Organization = new Organization();
$Organization->setRowid($idOrganization);
$Organization->fetchUsers();

$errors = array();
$success = false;

// for pagination
$offset = 30;

$tpl = "associateList.php";
$page = "controllers/admin/".$tpl;

if($action == "userUpdate")
{
    if($userId)
    {
        // check if the user belongs to the Organization
        $Organization->fetchUser($userId);
        $User = $Organization->getUser($userId);

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
            if($role !== false && in_array($role, [0, 1])) {
                $User->setAdmin($role);
            }

            try {
                $User->update();
                LogHistory::create($idUser, 'update', 'user', $userId, $firstname." ".$lastname, null, null, null, $idOrganization, "INFO", null, $ip, $page);
                
                $success = "L'utilisateur a bien été mis à jour.";
            } catch (exception $e) {
                $errors[] = "Le prénom n'a pas pu être modifié.";
                LogHistory::create($idUser, 'update', 'user', $userId, $firstname." ".$lastname, null, null, null, $idOrganization, "ERROR", $th->getMessage(), $ip, $page);
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
    if($userId)
    {
        // check if the user belongs to the organization
        $Organization->fetchUser($userId);
        $User = $Organization->getUser($userId);
        if($User)
        {
            try {
                $Organization->removeUser($userId);
                $User->delete();
                LogHistory::create($idUser, 'delete', 'user', $userId, $User->getFirstname()." ".$User->getLastname(), null, null, null, $idOrganization, "WARNING", null, $ip, $page);
                $success = "La suppression d'utilisateur a bien été effectuée.";
            } catch (\Throwable $th) {
                $errors[] = "La suppression d'utilisateur n'a pas pu aboutir.";
                LogHistory::create($idUser, 'delete', 'user', $userId, $User->getFirstname()." ".$User->getLastname(), null, null, null, $idOrganization, "ERROR", $th->getMessage(), $ip, $page);
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
