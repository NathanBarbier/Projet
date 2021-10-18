<?php
require_once 'layouts/entete.php';
?>

<div class="col-10">
    <div class="row position-relative">

        <?php if ($CurrentProject->active == 0) { ?>
            <div class="alert alert-info alert-visible mt-3 w-75 text-center position-absolute top-0 start-50 translate-middle-x">
                <i class="bi bi-info-circle-fill"></i>    
                Ce projet est archivé.
                &nbsp;&nbsp;<a href="<?= CONTROLLERS_URL ?>admin/detailsProjet.php?action=openProject&idProject=<?= $CurrentProject->rowid ?>" class="btn btn-outline-secondary">Ré-ouvrir</a>
                <button id="close-alert" type="button" class="btn-close position-absolute top-0 end-0 me-4 mt-3" aria-label="Close"></button>
            </div>
        <?php } ?>

        <?php if($errors) { ?>
        <div class="alert alert-danger mt-3 w-50 text-center position-absolute top-0 start-50 translate-middle-x">
        <?php foreach($errors as $error) { ?>
            <i class="bi bi-exclamation-triangle-fill"></i>
            <?php echo $error . "<br>";
        } ?>
        </div>
        <?php } else if ($success) { ?>
        <div class="alert alert-success mt-3 w-50 text-center position-absolute top-0 start-50 translate-middle-x">
            <i class="bi bi-check-circle-fill"></i>
            <?= $success; ?>
        </div>
        <?php } ?>
        <div class="sticker col-3 mt-2 ms-3 me-3 text-center overflow-x" style="height: 60px; ">
            <h3 class="mt-2"><?= $CurrentProject->name ?? "<b style='color:red'>No title</b>"; ?></h3>
        </div>
        
        <div class="sticker col mt-2 me-4 text-center">
            <h3 id="team-title" class="text-center mt-2">Modification des équipes</h3>
        </div>
    </div>

    <div class="row" style="height: 81vh;">
        <div class="sticker col-3 mt-3 ms-3 me-3 pb-4 text-center h-auto">
            <div class="row position-relative h-100">
                <form action="<?= CONTROLLERS_URL ?>admin/detailsProjet.php?action=updateProject&idProject=<?= $idProject ?>" method="POST">
                    <h5 class="mt-5 border-bottom w-50 mx-auto">Titre</h5>
                    <input class="sticker text-center mt-2 px-2" name="projectName" id="projectName" type="text" value="<?= $CurrentProject->name ?>">
    
                    <h5 class="mt-3 border-bottom w-50 mx-auto">Description</h5>
                    <textarea class="sticker text-center mt-2 pt-3 px-2" style="height: 150px;" name="description" id="description" type="text"><?= $CurrentProject->description ?></textarea>
    
                    <h5 class="mt-3 border-bottom w-50 mx-auto">Type</h5>
                    <input class="sticker text-center mt-2 px-2" name="type" id="type" type="text" value="<?= $CurrentProject->type ?>">
    
                    <div class="position-absolute translate-middle-x bottom-0 start-50 w-100">
                        <div class="row mx-auto">
                            <div class="col-6">
                                <button class="btn btn-outline-primary w-100" type="submit">Mettre à jour</button>
                            </div>
                            <div class="col-6">
                                <a id="map-btn" href="<?= CONTROLLERS_URL ?>admin/map.php?projectId=<?= $CurrentProject->rowid ?>" class="btn btn-outline-info w-100">Tableau</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="sticker col mt-3 me-4 text-center h-auto">
            <div class="row">
                <div class="col-4 mt-3 position-relative">
                    <button id="create-switch-button" class="btn btn-secondary w-75 collapse show">Création des équipes</button>
                    <button id="update-switch-button" class="btn btn-secondary w-75 collapse">Modification des équipes</button>

                    <h5 class="mt-3 border-bottom w-50 mx-auto">Nom équipe</h5>
                    <input id="teamName" class="sticker mt-1 w-100 text-center" type="text">

                    <!-- AFFICHER TOUTES LES EQUIPES EXISTANTES POUR CE PROJET -->
                    <h5 id="project-teams-title" class="mt-3 border-bottom w-50 mx-auto collapse show">Équipes existantes</h5>
                    <div id="project-teams-div" class="sticker mt-3 overflow-y bg-white pt-2 collapse show" style="height: 45%;">
                        <?php foreach($CurrentProject->teams as $team) { ?>
                            <div id="team-sticker-<?= $team->rowid ?>" onclick="showTeamMembers(<?= $team->rowid ?>, '<?= $team->name ?>')" class="sticker mx-2 mt-2 pt-3 hover">
                                <p><?= $team->name ?></p>
                            </div>
                        <?php } ?>
                    </div>

                    <form id="update-team-form" action="<?= CONTROLLERS_URL ?>admin/detailsProjet.php?action=updateTeam&idProject=<?= $idProject ?>" method="POST">
                        <input type="hidden" value="" name="teamNameUpdate" id="teamName-hidden-update">
                        <input type="hidden" value="" name="teamId" id="team-id-update-input">
                        <div class="position-absolute start-50 translate-middle-x bottom-0 w-100">
                            <div class="row mx-auto">
                                <div class="col-6">
                                    <a id="update-team-button" class="w-100 btn btn-outline-primary text-center collapse show">Mettre à jour</a>
                                </div>
                                <div class="<?= $CurrentProject->active ? 'col-3' : 'col-6' ?>">
                                    <a href="<?= CONTROLLERS_URL ?>admin/detailsProjet.php?action=deleteTeam&idProject=<?= $CurrentProject->rowid ?>" id="delete-team-button" class="w-100 btn btn-outline-danger text-center collapse show"><i class="bi bi-trash-fill"></i></a>
                                </div>
                                <?php if($CurrentProject->active) { ?>
                                    <div id="archive-btn" class="col-3 collapse show">
                                        <a href="<?= CONTROLLERS_URL ?>admin/detailsProjet.php?action=archive&idProject=<?= $CurrentProject->rowid ?>" class="w-100 btn btn-outline-danger text-center collapse show"><i class="bi bi-archive-fill"></i></a>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </form>

                    <form id="add-team-form" action="<?= CONTROLLERS_URL ?>admin/detailsProjet.php?action=addTeam&idProject=<?= $idProject ?>" method="POST">
                        <input type="hidden" value="" name="teamName" id="teamName-hidden-create">
                        <a id="create-team-button" class="btn btn-outline-primary w-75 text-center collapse position-absolute bottom-0 translate-middle-x">Créer l'équipe</a>
                    </form>
                </div>
                
                <div class="col-4 mt-3">
                    <div class="card w-100" style="height: 70vh;">
                        <div class="card-header text-center">
                            <h3>Membres affectés</h3>
                        </div>
                        <div class="card-body overflow-x">
                            <table class="table">
                                <tbody class="tbodyEquipeProjet">
                                    <tr>
                                        <th>Nom</th>
                                        <th>Prénom</th>
                                        <th>Options</th>
                                    </tr>
                                </tbody>

                                <!-- Membres en cours d'affectation -->
                                <?php
                                foreach($projectFreeUsers as $key => $user)
                                {
                                    ?>
                                    <tr class="collapse" id="adding-user-<?= $user->rowid ?>">
                                        <td><?= $user->lastname ?></td>
                                        <td><?= $user->firstname ?></td>
                                        <td><button onclick="toggleUserToTeam(<?= $user->rowid ?>)" class="btn btn-outline-danger">Retirer</button></td>
                                    </tr>
                                    <?php
                                } ?>

                                <!-- Membres déjà affectés aux équipes déjà créées -->
                                <?php
                                foreach($CurrentProject->teams as $team) {
                                    foreach($team->members as $member) { ?>
                                        <tr class="team-members-<?= $team->rowid ?> collapse" id="adding-again-user-<?= $member->rowid ?>">
                                            <td><?= $member->lastname ?></td>
                                            <td><?= $member->firstname ?></td>
                                            <td><button onclick="toggleUserToExistingTeam(<?= $member->rowid ?>)" class="btn btn-outline-danger">Retirer</button></td>
                                        </tr>
                                    <?php }
                                } ?>
                                </div>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-4 mt-3">
                    <div class="card w-100" style="height: 70vh;">
                        <div class="card-header text-center">
                            <h3>Membres prêts</h3>
                        </div>
                        <div class="card-body overflow-x">
                            <table class="table">
                                <tbody class="text-start">
                                    <tr>
                                        <th>Nom</th>
                                        <th>Prénom</th>
                                        <th>Options</th>
                                    </tr>
                                    <?php foreach($projectFreeUsers as $key => $user) { ?>
                                        <tr class="collapse show" id="free-user-<?= $user->rowid ?>">
                                            <td><?= $user->lastname ?></td>
                                            <td><?= $user->firstname ?></td>
                                            <td><button onclick="toggleUserToTeam(<?= $user->rowid ?>)" class="btn btn-outline-success">Ajouter</button></td>
                                        </tr>
                                    <?php } ?>
                                    <!-- Membres en cours de désaffectation des équipe déjà créées -->
                                    <?php foreach($CurrentProject->teams as $team) {
                                        foreach($team->members as $member) { ?>
                                            <tr class="freeing-team-members-<?= $team->rowid ?> collapse" id="freeing-user-<?= $member->rowid ?>">
                                                <td><?= $member->lastname ?></td>
                                                <td><?= $member->firstname ?></td>
                                                <td><button onclick="toggleUserToExistingTeam(<?= $member->rowid ?>)" class="btn btn-outline-success">Ajouter</button></td>
                                            </tr>
                                        <?php }
                                    } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            const CONTROLLERS_URL = <?php echo json_encode(CONTROLLERS_URL); ?>;
            const projectId = <?php echo json_encode($CurrentProject->rowid); ?>;
            var projectTeamsIds = <?php echo json_encode($projectTeamsIds); ?>;
            var freeUsersIds = <?php echo json_encode($projectFreeUsersIds); ?>;
            var CurrentProject = <?php echo json_encode($CurrentProject); ?>;
        </script>
        <script type="text/javascript" src="<?= JS_URL ?>admin/detailsProjet.js"></script>
<?php 
require_once 'layouts/pied.php';
?>