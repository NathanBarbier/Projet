<?php
//import all models
require_once "../../traitements/header.php";

$idUser = $_SESSION["idUtilisateur"] ?? null;
$rights = $_SESSION["habilitation"] ?? false;
$idOrganisation = $_SESSION["idOrganisation"] ?? false;

if($rights === "user")
{    
    $action = GETPOST('action');
    $firstname = GETPOST('firstname');
    $lastname = GETPOST('lastname');
    $email = GETPOST('email');

    $User = new User($idUser);
    $Poste = new Poste($User->getIdPoste());
    $idRole = $Poste->getIdRole();
    $Role = new Role($idRole);

    
    // Les équipes auxquelles l'user appartient
    $BelongsTo = new BelongsTo($idUser);

    $userProjects = array();

    // On récupère toutes les ids équipes auxquelle appartient l'user
    foreach($BelongsTo->getTeamIds() as $teamId)
    {
        $WorkTo = new WorkTo($teamId);
        $Equipe = new Equipe($teamId);
        
        // on récupère l'id du projet lié à cette équipe
        $idProjet = $WorkTo->getProjectId();

        $Project = new Projet($idProjet);
        $ProjectTasks = new ProjectTasks($idProjet);

        // On affecte pour chaque projets sur lequel travaille l'user
        $userProjects[$idProjet] = [
            'membersCount' => $Project->fetch_members_count(),
            'tasksCount' => $ProjectTasks->getTaskIds(),
            'projectName' => $Project->getNom(),
            'nomEquipe' => $Equipe->getNom(),
        ];

    }
    
    $tpl = "profil.php";
    
    $erreurs = array();
    $success = false;
    
    $data = new stdClass;

    // var_dump($action);
    // exit;

    if($action == 'userUpdate')
    {
        if($firstname && $lastname && $email)
        {
            if(filter_var($email, FILTER_VALIDATE_EMAIL))
            {
                $status = $User->updateInformations($firstname, $lastname, $email);

                if($status)
                {
                    $success = "Vos informations ont bien été mises à jour.";
                }
                else
                {
                    $erreurs[] = "Une erreur est survenue.";
                }
            }
            else
            {
                $erreurs[] = "L'adresse email n'est pas valide.";
            }
        }
    }


    if($success)
    {
        $User = new User($idUser);
        $Poste = new Poste($User->getIdPoste());
        $Equipe = new Equipe($User->getIdEquipe());
        $idRole = $Poste->getIdRole();
        $Role = new Role($idRole);
        $Projet = new Projet($Equipe->getidProjet());
    }

    
    
    $data = array(
        // 'avatar' => $User->getAvatar(),
        'firstname' => $User->getFirstname(),
        'lastname' => $User->getLastname(),
        'email' => $User->getEmail(),
        'nomPoste' => $Poste->getNom(),
        'role' => $Role->getNom(),
        'erreurs' => $erreurs,
        'success' => $success,
        'userProjects' => $userProjects,
    );
    
    $data = json_encode($data);
    
    header("location:".VIEWS_URL."membres/".$tpl."?data=$data");
}
else
{
    header("location:".ROOT_URL."index.php");
}

?>