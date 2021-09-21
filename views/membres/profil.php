<?php require_once "layouts/entete.php";

$data = json_decode(GETPOST('data'));

?>


<div class="col-10 mt-3" style="height: 100%;">
<?php

if($data->success)
{ ?>
    <div class="alert alert-success mx-3">
        <?= $data->success ?>
    </div>
    <?php 
}
else if ($data->erreurs)
{ ?>
    <div class="alert alert-danger mx-3">
        <?php 
        foreach($data->erreurs as $erreur)
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
                    if($data->userProjects)
                    {
                        foreach($data->userProjects as $project)
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
                    <?= $data->nomPoste; ?>
                </div>
                <!-- <div class="col-3 sticker text-center pt-2 mt-3">
                    <b>Equipe : </b>
                    <?= $data->nomEquipe; ?>
                </div> -->
                <div class="col-3 sticker text-center pt-2 mt-3">
                    <b>Role : </b>
                    <?= $data->role; ?>
                </div>
                <?php

                
                
                ?>
            </div>
    
        </div> 

        <!-- user properties -->
        <div class="col-4 profile-section mt-3" style="height:87%">
            <div class="text-center" style="width:150px; height:150px;margin-left:auto; margin-right:auto">
                <img class="mt-5" src="<?= IMG_URL ?>user.png" alt="" width="100px" style="border: 2px black solid;">
            </div>

            <div class="d-flex justify-content-center" style="height: 50%;">



                <div class="mt-5 text-center w-75">
                    <form action="<?= CONTROLLERS_URL ?>membres/profil.php?action=userUpdate" method="POST">

                        <input type="text" name="lastname" class="sticker form-control mt-4 pt-2 text-center" value="<?= $data->lastname ?>">
                        <input type="text" name="firstname" class="sticker form-control mt-4 pt-2 text-center" value="<?= $data->firstname ?>">
                        <input type="email" name="email" class="sticker form-control mt-4 pt-2 text-center" value="<?= $data->email ?>">
    
                        <button type="submit" class="w-50 mt-5 pt-2 btn btn-outline-primary text-center">Update</button>
                    </form>
                <!-- <div class="mt-3 w-75"> -->
                    <?php 
                    // $data->avatar 
                    ?>
                <!-- </div> -->
                </div>



            </div>
        </div>

    </div>


<?php
require_once "layouts/pied.php" ?>