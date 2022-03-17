<?php
require('../vendor/autoload.php');
require_once('../services/header.php');

use Faker\Factory;

$faker = Faker\Factory::create();

try {
    for($i = 0; $i < 4000; $i++) {
      
        $Task = new Task();

        // min & max columns rowid in database
        $fk_column = $faker->numberBetween(401, 1446);
        $Task->setFk_column($fk_column);

        // get the organization id related to the $fk_column
        $sql = "SELECT o.rowid FROM storieshelper_organization AS o 
        INNER JOIN storieshelper_project AS p ON o.rowid = p.fk_organization
        INNER JOIN storieshelper_team AS t ON p.rowid = t.fk_project
        INNER JOIN storieshelper_map_column AS m ON t.rowid = m.fk_team
        WHERE m.rowid = $fk_column";

        $PDO = new PDO('mysql:host=localhost;dbname=storieshelper;charset=UTF8', 'root');
        $requete = $PDO->prepare($sql);
        $result = $requete->execute();

        var_dump($sql);

        if($result) {
            $obj = $requete->fetch(PDO::FETCH_OBJ);

            $fk_organization = $obj->rowid;
        }

        $Organization = new Organization($fk_organization);

        // get users belonging to a team
        $usersTab = array();

        foreach($Organization->getProjects() as $Project) {
            foreach($Project->getTeams() as $Team) {
                foreach($Team->getUsers() as $User) {
                    $usersTab[] = $User->getRowid();
                }
            }
        }

        $Task->setFk_user($usersTab[$faker->numberBetween(min(array_keys($usersTab)), max(array_keys($usersTab)))]);
        $Task->setActive($faker->numberBetween(0,1));

        $rank = $Task->create();

        $Task->setname($faker->sentence());
        $Task->setdescription($faker->paragraph());
        $Task->setRank($rank);
        $Task->setactive($faker->numberBetween(0,1));
        $Task->setfinished_at('');
        $Task->setcreated_at($faker->date());

        $Task->update();
    }
} catch (\Throwable $th) {
    print $th;
}

?>


