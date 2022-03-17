<?php
require('../vendor/autoload.php');
require_once('../services/header.php');

use Faker\Factory;

$faker = Faker\Factory::create();

try {
    for($i = 0; $i < 2000; $i++) {
        
        $User = new User();
    
        $User->setLastname($faker->lastName);
        $User->setFirstname($faker->firstName);
        $User->setBirth($faker->date());
        $User->setEmail($faker->unique()->safeEmail);
        $User->setFk_organization($faker->randomDigit);
        $User->setPassword($faker->password(8,100));
        $User->setConsent($faker->numberBetween(0,1));
    
        $tab = array(0,0,0,0,0,0,0,0,0,0,0,0,1);
        $User->setAdmin($tab[$faker->numberBetween(0,12)]);
    
        $User->create();
    }
} catch (\Throwable $th) {
    print $th;
}

?>