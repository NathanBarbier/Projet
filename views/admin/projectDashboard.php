<?php
require_once 'layouts/header.php';
?>
    <div class="row" style="height: 90%;">
    <?php if($idProject) { ?>
        <div class="col-sm-12 col-md-3 px-sm-4 px-md-2 ps-md-4 text-center">
            <div class="row sticker overflow-x mx-sm-2 mx-md-0">
                <h3 class="mt-2"><?= $Project->getName() ?? "<b style='color:red'>No title</b>"; ?></h3>
            </div>

            <div class="row sticker mt-2 position-relative mx-sm-2 mx-md-0" style="height: 95%;">
                <form action="<?= CONTROLLERS_URL ?>admin/projectDashboard.php?action=updateProject&idProject=<?= $Project->getRowid() ?>" method="POST" style="margin-bottom: 0;">
                    <h5 class="mt-5 border-bottom w-50 mx-auto">Titre</h5>
                    <input class="sticker form-control text-center mt-2 px-2" style="max-width:inherit" name="projectName" id="projectName" type="text" value="<?= $Project->getName() ?>">
    
                    <h5 class="mt-3 border-bottom w-50 mx-auto">Description</h5>
                    <textarea class="sticker form-control text-center mt-2 pt-3 px-2" style="height: 150px;max-width:inherit" name="description" id="description" type="text"><?= $Project->getDescription() ?></textarea>
    
                    <h5 class="mt-3 border-bottom w-50 mx-auto">Type</h5>
                    <input class="sticker form-control text-center mt-2 px-2" style="max-width:inherit" name="type" id="type" type="text" value="<?= $Project->getType() ?>">
    
                    <div class="w-100">
                        <div class="row mx-auto mt-3">
                            <div class="col-5 p-md-1">
                                <button class="custom-button w-100 d-flex justify-content-center pt-2" type="submit" tabindex="0" data-bs-toggle="tooltip" title="Mettre à jour les informations du projet">
                                    <img class="save-img" src="<?= ASSETS_URL ?>images/save.png" alt="save">
                                </button>
                            </div>
                            <div class="col p-md-1">
                                <a id="map-btn" href="<?= CONTROLLERS_URL ?>admin/map.php?projectId=<?= $Project->getRowid() ?>" class="custom-button info w-100 pt-2" tabindex="0" data-bs-toggle="tooltip" title="Tableau de l'équipe"><i class="bi bi-layout-three-columns big-icon"></i></a>
                            </div>
                            <?php if($Project->isActive()) { ?>
                                <div id="archive-btn" class="col collapse show p-md-1">
                                    <a href="<?= CONTROLLERS_URL ?>admin/projectDashboard.php?action=archive&idProject=<?= $Project->getRowid() ?>" class="w-100 custom-button danger text-center collapse show pt-2" tabindex="0" data-bs-toggle="tooltip" title="Archiver le projet"><i class="bi bi-archive-fill big-icon"></i></a>
                                </div>
                            <?php } else { ?>
                                <div id="unarchive-btn" class="col collapse show p-md-1">
                                    <a href="<?= CONTROLLERS_URL ?>admin/projectDashboard.php?action=unarchive&idProject=<?= $Project->getRowid() ?>" class="w-100 custom-button success text-center collapse show pt-2" tabindex="0" data-bs-toggle="tooltip" title="Désarchiver le projet"><i class="bi bi-archive-fill big-icon"></i></a>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="col px-sm-4 px-md-2 text-center pe-md-3 mt-sm-5 mt-md-0">
            <div class="row sticker mx-sm-2 mx-md-0">
                <h3 id="team-title" class="text-center mt-2">Modification des équipes</h3>
            </div>

            <div class="row sticker mt-2 mx-sm-2 mx-md-0" style="height: 95%;">
                <div class="col-4 mt-3 position-relative">
                    <button id="create-switch-button" class="custom-button secondary px-1 w-75 collapse show">Création des équipes</button>
                    <button id="update-switch-button" class="custom-button secondary px-1 w-75 collapse">Modification des équipes</button>

                    <h5 class="mt-3 border-bottom w-50 mx-auto">Nom équipe</h5>
                    <input id="teamName" class="sticker form-control mt-1 w-100 text-center" type="text">

                    <!-- AFFICHER TOUTES LES EQUIPES EXISTANTES POUR CE PROJET -->
                    <h5 id="project-teams-title" class="mt-3 border-bottom w-50 mx-auto collapse show">Équipes existantes</h5>
                    <div id="project-teams-div" class="sticker mt-3 overflow-y bg-white pt-2 collapse show" style="height: 220px;">
                        <?php foreach($Project->getTeams() as $Team) { ?>
                            <div id="team-sticker-<?= $Team->getRowid() ?>" onclick="showTeamMembers(<?= $Team->getRowid() ?>, '<?= addslashes($Team->getName()) ?>')" class="<?= !$Team->isActive() ? 'archived-team ' : '' ?>sticker mx-2 mt-2 pt-3 hover">
                                <p><?= $Team->getName() ?></p>
                            </div>
                        <?php } ?>
                    </div>

                    <form id="update-team-form" action="<?= CONTROLLERS_URL ?>admin/projectDashboard.php?action=updateTeam&idProject=<?= $Project->getRowid() ?>" method="POST">
                        <input type="hidden" value="" name="teamNameUpdate" id="teamName-hidden-update">
                        <input type="hidden" value="" name="teamId" id="team-id-update-input">
                        <div class="w-100">
                            <div class="row mx-auto pt-2">
                                <div class="col-7 p-md-1">
                                    <a id="update-team-button" class="w-100 custom-button text-center collapse show pt-2" tabindex="0" data-bs-toggle="tooltip" title="Mettre à jour les informations de l'équipe">
                                        <img class="save-img" src="<?= ASSETS_URL ?>images/save.png" alt="save">
                                    </a>
                                </div>
                                <div class="col-5 p-md-1">
                                    <div class="d-flex justify-content-center">
                                        <a href="<?= CONTROLLERS_URL ?>admin/projectDashboard.php?action=deleteTeam&idProject=<?= $Project->getRowid() ?>" id="delete-team-button" class="w-100 custom-button danger collapse show pt-2" tabindex="0" data-bs-toggle="tooltip" title="Supprimer l'équipe">
                                            <i class="bi bi-trash-fill big-icon"></i>
                                        </a>
                                    </div>
                                </div>
                                <div class="col px-0">
                                    <div class="d-flex justify-content-center">
                                        <a id="archive-team-button" class="w-100 custom-button danger collapse pt-2 mx-1" tabindex="0" data-bs-toggle="tooltip" title="Archiver l'équipe">
                                            <i class="bi bi-archive-fill big-icon"></i>
                                        </a>
                                    </div>
                                    <div class="d-flex justify-content-center">
                                        <a id="open-team-button" class="w-100 custom-button success collapse pt-2 mx-1" tabindex="0" data-bs-toggle="tooltip" title="Désarchiver l'équipe">
                                            <i class="bi bi-archive-fill big-icon"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                    <form id="add-team-form" action="<?= CONTROLLERS_URL ?>admin/projectDashboard.php?action=addTeam&idProject=<?= $Project->getRowid() ?>" method="POST">
                        <input type="hidden" value="" name="teamName" id="teamName-hidden-create">
                        <a id="create-team-button" class="custom-button w-75 text-center collapse pt-2">Créer l'équipe</a>
                    </form>
                </div>
                
                <div class="col-4 mt-3">
                    <div class="card w-100" style="height: 70vh;">
                        <div class="card-header text-center">
                            <h3>Membres affectés</h3>
                        </div>
                        <div class="card-body overflow-x overflow-y">
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
                                foreach($freeUsers as $key => $User)
                                { ?>
                                    <tr class="collapse" id="adding-user-<?= $User->getRowid() ?>">
                                        <td><?= $User->getLastname() ?></td>
                                        <td><?= $User->getFirstname() ?></td>
                                        <td><button onclick="toggleUserToTeam(<?= $User->getRowid() ?>)" class="custom-button danger px-2">Retirer</button></td>
                                    </tr>
                                    <?php
                                }

                                // Membres déjà affectés aux équipes déjà créées
                                foreach($Project->getTeams() as $Team) {
                                    foreach($Team->getUsers() as $User) { ?>
                                        <tr class="team-members-<?= $Team->getRowid() ?> collapse" id="adding-again-user-<?= $User->getRowid() ?>">
                                            <td><?= $User->getLastname() ?></td>
                                            <td><?= $User->getFirstname() ?></td>
                                            <td><button onclick="toggleUserToExistingTeam(<?= $User->getRowid() ?>)" class="custom-button danger px-2">Retirer</button></td>
                                        </tr>
                                        <?php
                                    }
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
                        <div class="card-body overflow-x overflow-y">
                            <table class="table">
                                <tbody class="text-start">
                                    <tr>
                                        <th>Nom</th>
                                        <th>Prénom</th>
                                        <th>Options</th>
                                    </tr>
                                    <?php foreach($freeUsers as $key => $User) { ?>
                                        <tr class="collapse show" id="free-user-<?= $User->getRowid() ?>">
                                            <td><?= $User->getLastname() ?></td>
                                            <td><?= $User->getFirstname() ?></td>
                                            <td><button onclick="toggleUserToTeam(<?= $User->getRowid() ?>)" class="custom-button success px-2">Ajouter</button></td>
                                        </tr>
                                    <?php } ?>
                                    <!-- Membres en cours de désaffectation des équipe déjà créées -->
                                    <?php foreach($Project->getTeams() as $Team) {
                                        foreach($Team->getUsers() as $User) { ?>
                                            <tr class="freeing-team-members-<?= $Team->getRowid() ?> collapse" id="freeing-user-<?= $User->getRowid() ?>">
                                                <td><?= $User->getLastname() ?></td>
                                                <td><?= $User->getFirstname() ?></td>
                                                <td><button onclick="toggleUserToExistingTeam(<?= $User->getRowid() ?>)" class="custom-button success px-2">Ajouter</button></td>
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

        <script type="text/javascript" src="<?= JS_URL ?>admin/projectDashboard.min.js" defer></script>
    <?php } ?>
<?php 
require_once 'layouts/footer.php';
?>