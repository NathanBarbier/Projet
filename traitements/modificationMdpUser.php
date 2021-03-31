<?php
require_once "header.php";
if(!empty($_POST['envoi']) && $_POST["envoi"] == 1)
{
    extract($_POST);
    // Tous les champs sont saisis
    if(!empty($oldmdp) && !empty($newmdp) && !empty($newmdp2))
    {
        if($newmdp === $newmdp2)
        {
            // regex vérification mdp
            // $regex = "/\d{1,100}|[A-Z]{1,100}|[a-z]{1,100}|[.!@#$%^&*()_+-=]{1,100}|[A-Za-z0-9.!@#$%^&*()_+-=]{8,100}/";
            // $space = "/\s/";  

            //Vérification des conditions de création du mot de passe
            if (/* preg_match_all($regex, $newmdp) != 5 || preg_match($space, $newmdp) != 0 */ strlen($newmdp) < 8 || strlen($newmdp) > 100)
            {
                header('location:../membres/modificationMdpUser.php?error=mdpRules');
                // Le mot de passe doit contenir entre 8 et 100 caractères, au moins un caractère spécial, une minuscule, une majuscule, un chiffre et ne doit pas contenir d'espace
            } else {

                $utilisateur = recupererHashMdpUser($newmdp);
                //vérification correspondance mdp
                if(!password_verify($oldmdp, $utilisateur["mdp"]))
                {
                    header('location:../membres/modificationMdpUser.php?error=incorrectMdp');
                    // L'ancien mot de passe est incorrect
                } else {
                    if($oldmdp == $newmdp){
                        header('location:../membres/modificationMdpUser.php?error=noChange');
                        // Le mot de passe n'a pas changé.
                    } else {
                        // Le mot de passe est correct
                        try
                        {
                            // Changement du mdp de l'utilisateur dans la Bdd
                            modifierMdpUtilisateur($newmdp);
                            header("location;../membres/modificationMdpUser.php?success");
                            
                        } catch (Exception $e) {
                            ?>
                                <div class="alert alert-danger">
                                    Erreur SQL : Le mot de passe n'a pas pu être changé.
                                </div>
                            <?php
                        }
                    }
                }
            }  
        } else {
            // Les deux mot de passes ne sont pas identiques
            header('location:../membres/modificationMdpUser.php?error=nonIdentiques');
        }
    } else {
        // Un des champs n'est pas rempli
        header('location:../membres/modificationMdpUser.php?error=missingInput');
    }
    // Afficher les erreurs
    if(count($erreurs) != 0)
    {
    ?>
        <div class="alert alert-danger">
            <?php
            foreach($erreurs as $erreur)
            {
                echo $erreur . "<br>";
            }
            ?>
        </div>
    <?php
    }
} else {
    header("location:../index.php");
}
?>