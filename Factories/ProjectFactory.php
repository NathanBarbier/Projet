<?php
require('../vendor/autoload.php');
require_once('../services/header.php');

use Faker\Factory;

$faker = Faker\Factory::create();

try {
    for($i = 0; $i < 100; $i++) {
        
        $Project = new Project();

        $projectnames = ['linkedin', 'instagram', 'facebook', 'xbox one', 'wii U', 'nintendo ds', 'computer', 'youtube', 'github', 'gitlab', 'trello', 'google translate', 'deepl translate', 'twitch', 'firefox', 'W3school', 'Dolibarr', 'programmez !', 'Développez !', 'zdnet', 'laravel', 'symphony', 'visual studio code', 'miscrosoft teams', 'windows 11', 'ubuntu'];

        $projectypes = ['business', 'software', 'hardware', 'website', 'marketing', 'news', 'informations', 'learning', 'cybersecurity', 'cryptocurrency', 'bank', 'trading', 'shopping', 'social', 'systems', 'operating systems', 'application', 'mobile', 'video game', 'board game', 'economy', 'ecology', 'political', 'coding', 'music', 'film', 'videos', 'series', 'voice on ip', 'research', 'tourism', 'hobby', 'tools', 'productivity', 'book', 'medicine'];

        $Project->setName($projectnames[$faker->numberBetween(min(array_keys($projectnames)), max(array_keys($projectnames)))]);
        $Project->setType($projectypes[$faker->numberBetween(min(array_keys($projectypes)), max(array_keys($projectypes)))]);
        $Project->setDescription($faker->sentence());
        $Project->setFk_organization($faker->numberBetween(3,10));

        $Project->create();
    }
} catch (\Throwable $th) {
    print $th;
}

?>