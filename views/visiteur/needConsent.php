<?php
require_once "layouts/entete.php";
?>

<div class="container mt-5">

    <?php if(!empty($errors)) { ?>
    <div class="position-relative mx-auto">
        <div class="alert alert-danger w-50 text-center position-absolute top-0 start-50 translate-middle-x">
        <?php foreach($errors as $error) { ?>
            <i class="bi bi-exclamation-triangle-fill"></i>
            <?php echo $error . "<br>";
        } ?>
        </div>
    </div>
    <?php } ?>
    
    <h1 class="text-center w-50 mx-auto" style="border-bottom: rgb(216, 214, 214) 1px solid;">Traitement de vos données</h1>

    <div class="sticker h-auto mt-3 pt-3 pb-5 w-75 mx-auto text-center">
        <p><b><span style="color: red;">Pour vous connecter vous devez consentir au traitement de vos données</span></b></p>

        <p class="mt-5"><b>J'accepte le traitement de mes données.</b></p>
        <a href="<?= CONTROLLERS_URL ?>visiteur/needConsent.php?action=giveConsent" id="give-consent" class="btn btn-outline-success w-25">Accepter</a>

        <p class="mt-5"><b>Je refuse le traitement de mes données. (Votre compte sera alors supprimé)</b></p>
        <a href="<?= CONTROLLERS_URL ?>visiteur/needConsent.php?action=refuseConsent" id="refuse-consent" class="btn btn-outline-danger w-25">Refuser</a>
    </div>
</div>

<?php
require_once "layouts/pied.php";
