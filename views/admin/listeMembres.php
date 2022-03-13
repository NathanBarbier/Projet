<?php
require_once "layouts/entete.php";
?>
    <!-- Modal -->
    <div class="modal" id="user-delete-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog position-absolute start-50 translate-middle" style="top:40%;">
            <div class="modal-content" style="height: inherit;">
                <div class="modal-body position-relative">
                    <div class="row text-center">
                        <h4 class="underline">Confirmation de suppression</h4>
                        <p class="mt-4">
                            Êtes-vous de vouloir supprimer cet utilisateur ?
                            <br>
                            Cette action est irréversible.
                        <p>

                    </div>

                    <div class="mt-4 row">
                        <div class="col-6 col-sm-12 mb-0 mb-sm-2 mb-md-0 col-md-6 text-end">
                            <form action="<?= CONTROLLERS_URL ?>admin/listeMembres.php">
                                <input type="hidden" name="action" value="userDelete">
                                <input type="hidden" id="delete-user-id" name="idUser" value="">
                                <button class="w-100 custom-button danger double-button-responsive">
                                    Confirmer
                                </button>
                            </form>
                        </div>
                        <div class="col-6 col-sm-12 col-md-6 text-start">
                            <button id="cancel-user-delete" class="w-100 custom-button warning double-button-responsive">
                                Annuler
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="position-relative overflow-y" style="height: 90vh;">
        <table class="table table-radius mt-4 position-absolute">
            <thead class="bg-white">
                <tr>
                    <th colspan="7">
                        <div style="float : left">
                            <h5 class="underline">Liste des membres</h5>
                        </div>
                        <div class="me-5" style="float : right">
                            <div>
                                <h5 class="underline">Nombres de membres : <?= count($Organization->getUsers()); ?></h5>
                            </div> 
                        </div>
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white">
                <tr class="text-center">
                    <th>NOM</th>
                    <th>Prénom</th>
                    <th>Adresse email</th>
                    <th>Droits</th>
                    <th>Options</th>
                </tr>
    
                <?php
                foreach($Organization->getUsers() as $User) {
                ?>
                <tr class="text-center">
                    <!-- Update user form -->
                    <form id="user-update-form" method="POST" action="<?= CONTROLLERS_URL ?>admin/listeMembres.php?action=userUpdate">
                        <td class="align-middle">
                            <div class="row">
                                <div class="col-12 col-sm-12 col-md-9 mx-auto">
                                    <input class="form-control mb-1 text-center w-100 mx-auto" value="<?= $User->getLastname() ?>" type="text" name="lastname" placeholder=" " required>
                                </div>
                            </div>
                        </td>
                        
                        <td class="align-middle">
                            <div class="row">
                                <div class="col-12 col-sm-12 col-md-9 mx-auto">
                                    <input class="form-control mb-1 text-center w-100 mx-auto" value="<?= $User->getFirstname() ?>" type="text" name="firstname" placeholder=" " required>
                                </div>
                            </div>
                        </td>
    
                        <td class="align-middle">
                            <div class="row">
                                <div class="col-12 col-sm-12 col-md-9 mx-auto">
                                    <input class="form-control mb-1 text-center w-100 mx-auto" value="<?= $User->getEmail() ?>" type="text" name="email" placeholder=" " required>
                                </div>
                            </div>
                        </td>
    
                        <td class="align-middle">
                            <b>
                                <?php if($User->isAdmin() == 1) {
                                    echo '<span style="color:red">Administrateur</span>';
                                } else {
                                    echo '<span style="color:grey">Utilisateur</span>';
                                } ?>
    
                                <?php if($User->getRowid() == $idUser) {
                                    echo '&nbsp;(You)';
                                } ?>
                            </b>
                        </td>
    
                        <!-- Options -->
                        <td class="align-middle">
                            <div class="mt-4 row">
                                <div class="col-12 col-sm-12 col-md-6 pb-2">
                                    <button type="button" id="user-delete-btn-<?= $User->getRowid() ?>" class="w-100 custom-button danger double-button-responsive px-1" style="min-width: max-content;">
                                        Supprimer
                                    </button>
                                </div>
    
                                <div class="col-12 col-sm-12 col-md-6">
                                    <input type="hidden" name="idUser" value="<?= $User->getRowid() ?>">
                                    <button onclick="document.getElementById('user-update-form').submit()" class="w-100 custom-button double-button-responsive px-1" style="min-width: max-content;">
                                        Mettre à jour
                                    </button>
                                </div>
                            </div>
                        </td>
                    </form>
                </tr>
                <?php
                }
    
                ?>
            </tbody>
        </table>
    </div>

    <nav class="mx-auto w-50 justify-content-center d-flex mt-3" aria-label="Page navigation">
        <ul class="pagination">
            <li id="previous-page" class="page-item"><a class="page-link">Previous</a></li>
            <!-- <li class="page-item"><a class="page-link" href="#">1</a></li>
            <li class="page-item"><a class="page-link" href="#">2</a></li>
            <li class="page-item"><a class="page-link" href="#">3</a></li> -->
            <li id="next-page" class="page-item"><a class="page-link">Next</a></li>
        </ul>
    </nav>

<script src="<?= JS_URL ?>admin/listeMembres.js" defer></script>

<?php
require_once "layouts/pied.php";
?>