<?php require_once "entete.php";
// Si le mdp a bien été changé
if(!empty($_GET["success"]))
{
    ?>
    <div class="alert alert-success">
    Le mot de passe a bien été changé<br>
    Vous allez être redirigé vers la vue globale<br>
    <a href="index.php">Cliquez ici si vous ne voulez pas attendre</a>
    </div>
    <?php
} else {
    // Si il y a une erreur
    if(!empty($_GET["error"])) { ?>
        <div class="alert alert-danger">
        <?php
        switch ($_GET["error"]) { case "missingInput":?>
            <?php echo "Erreur : Un champs n'est pas rempli."?>
            <?php break ?>
        <?php case "nonIdentiques": ?>
            <?php echo "Erreur : Les deux nouveaux mots de passes ne sont pas identiques."?>
            <?php break ?>
        <?php case 'mdpRules':?>
            <?php echo "Erreur : Le mot de passe doit contenir entre 8 et 100 caractères, au moins un caractère spécial, une minuscule, une majuscule, un chiffre et ne doit pas contenir d'espace."?>
            <?php break ?>
        <?php case 'incorrectMdp':?>
            <?php echo "Erreur : L'ancien mot de passe est incorrect."?>
            <?php break ?>
        <?php case 'noChange': ?>
            <?php echo "Erreur : Le mot de passe ne peut pas être le même qu'avant."?>
            <?php break;
        }
    }
}
?>
    <h1>Modification du mot de passe</h1>

    <a class="btn btn-primary" href="index.php">Retourner à la vue globale</a>

    <form method="post" action="../traitements/modificationMdpUser.php">
        
        <div class="form-group">
            <label for="oldmdp">Ancien mot de passe</label>
            <input class="form-control" type="password" required id="oldmdp" name="oldmdp">
        </div>

        <div class="form-group">
            <label for="newmdp">Nouveau mot de passe</label>
            <input class="form-control" type="password" required id="newmdp" name="newmdp">
        </div>

        <div class="form-group">
            <label for="newdmp2">Confirmer nouveau mot de passe</label>
            <input class="form-control" type="password" required id="newmdp2" name="newmdp2">
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-primary" name="envoi" value="1">Confirmer</button>
        </div>

    </form>

<?php
require_once "pied.php"; ?>