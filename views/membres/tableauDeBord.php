<?php 
require_once "layouts/entete.php";
?>


<div class="col-10 mt-3" style="height: 100%;">
<?php

if($success)
{ ?>
    <div class="alert alert-success mx-3">
        <?= $success ?>
    </div>
    <?php 
}
else if ($erreurs)
{ ?>
    <div class="alert alert-danger mx-3">
        <?php 
        foreach($erreurs as $erreur)
        {
            echo $erreur . "<br>";
        }
        ?>
    </div>
<?php
}

// var_dump($data);

?>

    <div class="row w-100" style="height: 100%;">

        <div class="col-8 mt-3" style="height:85%">
            <!-- PROJECT -->
            <div class="row profile-section mx-3" style="height: 50%;">
                <h1 class="text-center">Projet Actuel</h1>

                <div style="height: 80%; overflow: auto">
                    <?php 
                    if($userProjects)
                    {
                        foreach($userProjects as $project)
                        {
                        ?>
                        <div>
                            <div class="row ms-3 mt-3 sticker align-items-center" style="width: 90%;">
                                <b>Nom du projet : </b>
                                <?php 
                                $project->projectName
                                ?>
                                
                            </div>
                            <div class="row ms-3 mt-4 sticker align-items-center" style="width: 90%;">
                                <b>Equipe : </b>
                                <?php
                                $project->nomEquipe
                                ?>
                            </div>
                            <div class="row ms-3 mt-4 justify-content-around" style="width: 90%;">
                                <div class="sticker col-5 text-center">
                                    <b>Nb participants</b>
                                    <p class="text-center">HOLA</p>
                                    <br>
                                    <?php echo  $project->membersCount ?>
                                </div>
                                
                                <div class="sticker col-5 text-center">
                                    <b>Nb tâches</b>
                                    <br>
                                    <p class="text-center">HOLA</p>
                                    <?php echo $project->tasksCount ?>
                                </div>
                            </div>
                        </div>
                        <?php 
                        }
                    }
                    else
                    { ?>
                        <div class="sticker mx-auto mt-5 pt-3 text-center" style="width: 70%; height: 30%">
                            <h3>Vous n'avez encore aucun projet en cours.</h3>
                        </div>
                    <?php
                    } ?>

                </div>


            </div>
            
            <!-- poste equipe role -->
            <div class="row profile-section mt-3 mx-3 justify-content-around" style="height: 50%">
                
                <div class="col-3 sticker text-center pt-2 mt-3">
                    <b>Poste : </b>
                    <?= $nomPoste; ?>
                </div>
                <!-- <div class="col-3 sticker text-center pt-2 mt-3">
                    <b>Equipe : </b>
                    <?= $nomEquipe; ?>
                </div> -->
                <div class="col-3 sticker text-center pt-2 mt-3">
                    <b>Role : </b>
                    <?= $role; ?>
                </div>
                <?php

                
                
                ?>
            </div>
    
        </div> 

        <!-- user properties -->
        <div class="col-4 profile-section mt-3" style="height:87%">

            <h3 class="mx-auto mt-3 text-center" style="border-bottom: black solid 1px; border-color: rgb(216, 214, 214); width: 80%">Profil</h3>

            <div class="text-center" style="width:150px; height:150px;margin-left:auto; margin-right:auto">
                <img class="mt-4" src="<?= IMG_URL ?>user.png" alt="" width="100px" style="border: 2px black solid;">
            </div>

            <div class="d-flex justify-content-center" style="height: 50%;">



                <div class="mt-4 text-center w-75">
                    <form action="<?= CONTROLLERS_URL ?>membres/tableauDeBord.php?action=userUpdate" method="POST">

                        <input type="text" name="lastname" class="sticker form-control mt-4 pt-2 text-center" value="<?= $lastname ?>">
                        <input type="text" name="firstname" class="sticker form-control mt-4 pt-2 text-center" value="<?= $firstname ?>">
                        <input type="email" name="email" class="sticker form-control mt-4 pt-2 text-center" value="<?= $email ?>">
    
                        <button type="submit" class="w-50 mt-5 pt-2 btn btn-outline-primary text-center">Update</button>
                    </form>

                    <a class="btn btn-outline-secondary mt-5" href="<?= CONTROLLERS_URL ?>membres/passwordUpdate.php">Modifier mot de passe</a>
                <!-- <div class="mt-3 w-75"> -->
                    <?php 
                    // $avatar 
                    ?>
                <!-- </div> -->
                </div>



            </div>
        </div>

    </div>


<?php
require_once "layouts/pied.php" ?>