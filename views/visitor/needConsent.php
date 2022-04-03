<?php
require_once "../../services/constants.php";
require_once "layouts/header.php";
?>

<div class="container mt-5">
    
    <h1 class="text-center w-50 mx-auto" style="border-bottom: rgb(216, 214, 214) 1px solid;">Traitement de vos données</h1>

    <div class="sticker h-auto mt-3 pt-3 pb-5 w-75 mx-auto text-center">
        <p><b><span style="color: red;">Pour vous connecter vous devez consentir au traitement de vos données</span></b></p>

        <p class="mt-5"><b>J'accepte le traitement de mes données.</b></p>
        <div class="row justify-content-center">
            <div class="col-12 col-md-12 col-lg-6">
                <a href="<?= CONTROLLERS_URL ?>visitor/needConsent.php?action=giveConsent" id="give-consent" class="custom-button success pt-2 w-75">
                    Accepter
                </a>
            </div>
        </div>

        <p class="mt-5"><b>Je refuse le traitement de mes données. (Votre compte sera alors supprimé)</b></p>
        <div class="row justify-content-center">
            <div class="col-12 col-md-12 col-lg-6">
                <a href="<?= CONTROLLERS_URL ?>visitor/needConsent.php?action=refuseConsent" id="refuse-consent" class="custom-button danger pt-2 w-75">
                    Refuser
                </a>
            </div>
        </div>
    </div>
</div>

<?php
require_once "layouts/footer.php";
