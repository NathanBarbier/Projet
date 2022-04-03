<?php
require('../vendor/autoload.php');
require_once('../services/header.php');

use Faker\Factory;

$faker = Faker\Factory::create();

try 
{
    // Company Creation
    $Organization = new Organization();

    $Organization->setName($faker->company);

    $OrganizationId = $Organization->create();

    // create users
    // for ($i=0; $i < 1000; $i++) { 
    //     $User = new User();

    //     $User->setLastname($faker->lastName);
    //     $User->setFirstname($faker->firstName);
    //     $User->setBirth($faker->date());
    //     $User->setEmail($faker->unique()->email);
    //     $User->setFk_organization($OrganizationId);
    //     $User->setPassword($faker->password(8,100));
    //     $User->setConsent($faker->numberBetween(0,1));
    
    //     $tab = array(0,0,0,0,0,0,0,0,0,0,0,0,1);
    //     $User->setAdmin($tab[$faker->numberBetween(0,12)]);

    //     $UserId = $User->create();
    // }

    $projectnames = ['linkedin', 'instagram', 'facebook', 'xbox one', 'wii U', 'nintendo ds', 'computer', 'youtube', 'github', 'gitlab', 'trello', 'google translate', 'deepl translate', 'twitch', 'firefox', 'W3school', 'Dolibarr', 'programmez !', 'Développez !', 'zdnet', 'laravel', 'symphony', 'visual studio code', 'miscrosoft teams', 'windows 11', 'ubuntu'];

    $projectypes = ['business', 'software', 'hardware', 'website', 'marketing', 'news', 'informations', 'learning', 'cybersecurity', 'cryptocurrency', 'bank', 'trading', 'shopping', 'social', 'systems', 'operating systems', 'application', 'mobile', 'video game', 'board game', 'economy', 'ecology', 'political', 'coding', 'music', 'film', 'videos', 'series', 'voice on ip', 'research', 'tourism', 'hobby', 'tools', 'productivity', 'book', 'medicine'];

    // create projects
    for ($i=0; $i < 50 ; $i++) { 
        $Project = new Project();

        $Project->setName($projectnames[$faker->numberBetween(min(array_keys($projectnames)), max(array_keys($projectnames)))]);
        $Project->setType($projectypes[$faker->numberBetween(min(array_keys($projectypes)), max(array_keys($projectypes)))]);
        $Project->setDescription($faker->sentence());
        $Project->setFk_organization($OrganizationId);

        $rowid = $Project->create();
        $Project->setRowid($rowid);

        // create teams
        for ($i=0; $i < 20; $i++) { 
            $Team = new Team();

            $Team->setName($faker->jobTitle);
            $Team->setFk_project($Project->getRowid());
            $Team->setActive($faker->numberBetween(0,1));
    
            $TeamId = $Team->create();
            $Team->setRowid($TeamId); 

            for ($i=0; $i < 5 ; $i++) { 
                // create users
                $User = new User();

                $User->setLastname($faker->lastName);
                $User->setFirstname($faker->firstName);
                $User->setBirth($faker->date());
                $User->setEmail($faker->unique()->email);
                $User->setFk_organization($OrganizationId);
                $User->setPassword($faker->password(8,100));
                $User->setConsent($faker->numberBetween(0,1));
            
                $tab = array(0,0,0,0,0,0,0,0,0,0,0,0,1);
                $User->setAdmin($tab[$faker->numberBetween(0,12)]);
        
                $UserId = $User->create();

                // link the user to the team
                $BelongsTo = new BelongsTo();
                $BelongsTo->setFk_team($TeamId);
                $BelongsTo->setFk_user($UserId);
                $BelongsTo->create();

                // create tasks
                for ($i=0; $i < 2; $i++) { 

                    $randomInt = $faker->numberBetween(0,3);
                    $MapColumnRepository = new MapColumnRepository();
                    $fk_column = $MapColumnRepository->fetchRandomFk_column($TeamId, $randomInt);

                    $Task = new Task();
                    
                    $Task->setFk_column($fk_column);
                    $Task->setFk_user($UserId);
                    $Task->setActive($faker->numberBetween(0,1));
            
                    $rank = $Task->create();
            
                    $Task->setname($faker->sentence());
                    $Task->setdescription($faker->paragraph());
                    $Task->setRank($rank);
                    $Task->setactive($faker->numberBetween(0,1));
                    $Task->setfinished_at('');
                    $Task->setcreated_at($faker->date());
            
                    $Task->update();
        
                    $TaskId = $Task->fetch_last_insert_id();

                    // create task comment
                    $TaskComment = new TaskComment();

                    $TaskComment->setFk_task($TaskId);
                    $TaskComment->setNote($faker->sentence());
                    $TaskComment->setFk_user($UserId);
    
                    $TaskComment->create();

                    // create task member
                    $TaskMember = new TaskMember();

                    $TaskMember->setFk_task($TaskId);
                    $TaskMember->setFk_user($UserId);
                    
                    $TaskMember->create();
                }
            }
        }
    }
} 
catch (\Throwable $th) 
{
    print $th;
}

?>