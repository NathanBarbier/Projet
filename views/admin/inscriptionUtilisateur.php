<?php 
require_once "layouts/entete.php";
?>
    <div class="container">
        <form class="mx-auto position-relative col-md-12 col-lg-6 mx-auto border-lg bg-white px-4 pb-3" method="post" action="<?= CONTROLLERS_URL ?>admin/inscriptionUtilisateur.php?action=signup">
            <h1 class="text-center mx-auto w-100 underline">Inscription d'un collaborateur</h1>
            <hr>
                
            <div class="form-floating mt-3 mb-3">
                <input class="form-control" type="email" name="email" id="email" placeholder="adresse email" value="<?= $email ?? ""?>"  required>
                <label for="email">Adresse email</label>
            </div>

            <div class="form-floating mb-3">
                <input class="form-control" type="text" name="lastname" id="lastname" placeholder="Nom" value="<?= $lastname ?? ""?>"  required>
                <label for="nom">Nom</label>
            </div>

            <div class="form-floating mb-3">
                <input class="form-control" type="text" name="firstname" id="firstname" placeholder="Prénom" value="<?= $firstname ?? ""?>"  required>
                <label for="prenom">Prénom</label>
            </div>
            
            <div class="form-floating mb-3">
                <input class="form-control" type="date" name="birth" id="birth" placeholder="AAAA-MM-JJ" value="<?= $birth ?? ""?>"  required>
                <label for="dateNaiss">Date de naissance</label>
            </div>

            <div class="text-center mt-5">
                <button type="submit" class="custom-button w-50" name="envoi" value="1">Inscrire</button>
            </div>

        </form>
    </div>
</div>
<?php

require_once 'layouts/pied.php';
?>