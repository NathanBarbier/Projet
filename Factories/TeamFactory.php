<?php
require('../vendor/autoload.php');
require_once('../services/header.php');

use Faker\Factory;

$faker = Faker\Factory::create();

try {
    for($i = 0; $i < 250; $i++) {
        
        $Team = new Team();

        $Team->setName($faker->jobTitle);
        $Team->setFk_project($faker->numberBetween(25,100));
        $Team->setActive($faker->numberBetween(0,1));

        $Team->create();
    }
} catch (\Throwable $th) {
    print $th;
}

?>