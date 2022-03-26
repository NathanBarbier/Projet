<?php
require('../vendor/autoload.php');
require_once('../services/header.php');

use Faker\Factory;

$faker = Faker\Factory::create();

try {
    for($a = 0; $a < 10; $a++) {
        
        // Company Creation
        $Organization = new Organization();

        $Organization->setName($faker->company);

        $Organization->create();

        $Projects = array();
        $ProjectsIds = array();

        $Users = array();
        $UsersIds = array();

        $Teams = array();
        $TeamsIds = array();

        // Project creation
        for($i = 0; $i < 20; $i++) {
        
            $Project = new Project();
    
            $projectnames = ['linkedin', 'instagram', 'facebook', 'xbox one', 'wii U', 'nintendo ds', 'computer', 'youtube', 'github', 'gitlab', 'trello', 'google translate', 'deepl translate', 'twitch', 'firefox', 'W3school', 'Dolibarr', 'programmez !', 'Développez !', 'zdnet', 'laravel', 'symphony', 'visual studio code', 'miscrosoft teams', 'windows 11', 'ubuntu'];
    
            $projectypes = ['business', 'software', 'hardware', 'website', 'marketing', 'news', 'informations', 'learning', 'cybersecurity', 'cryptocurrency', 'bank', 'trading', 'shopping', 'social', 'systems', 'operating systems', 'application', 'mobile', 'video game', 'board game', 'economy', 'ecology', 'political', 'coding', 'music', 'film', 'videos', 'series', 'voice on ip', 'research', 'tourism', 'hobby', 'tools', 'productivity', 'book', 'medicine'];
    
            $Project->setName($projectnames[$faker->numberBetween(min(array_keys($projectnames)), max(array_keys($projectnames)))]);
            $Project->setType($projectypes[$faker->numberBetween(min(array_keys($projectypes)), max(array_keys($projectypes)))]);
            $Project->setDescription($faker->sentence());
            $Project->setFk_organization($a+1);
    
            $rowid = $Project->create();
            $Project->setRowid($rowid);
            $Projects[] = $Project;
            $ProjectsIds[] = $rowid;
        }

        // Users Creation
        for($i = 0; $i < 500; $i++) {
        
            $User = new User();
        
            $User->setLastname($faker->lastName);
            $User->setFirstname($faker->firstName);
            $User->setBirth($faker->date());
            $User->setEmail($faker->unique()->email);
            $User->setFk_organization($a+1);
            $User->setPassword($faker->password(8,100));
            $User->setConsent($faker->numberBetween(0,1));
        
            $tab = array(0,0,0,0,0,0,0,0,0,0,0,0,1);
            $User->setAdmin($tab[$faker->numberBetween(0,12)]);
        
            $rowid = $User->create();
            $Users[] = $User;
            $UsersIds[] = $rowid;
        }

        // Teams creation
        foreach($Projects as $Project) {
        
            for($i = 0; $i < 10; $i++) {
                $Team = new Team();
        
                $Team->setName($faker->jobTitle);
                $Team->setFk_project($Project->getRowid());
                $Team->setActive($faker->numberBetween(0,1));
        
                $rowid = $Team->create();
                $Team->setRowid($rowid);
                $Teams[] = $Team;
                $TeamsIds[] = $rowid;
            }
        }

        // link users to teams
        foreach($Projects as $Project) {

            foreach($Teams as $Team) {

                if($Team->getfk_project() == $Project->getRowid()) {
                    // get 10 random users
                    $randomusers = array();
    
                    for($i = 0; $i < 10; $i++) {
                        $index = $faker->numberBetween(0,499);
    
                        while (in_array($UsersIds[$index], $randomusers)) {
                            $index = $faker->numberBetween(0,499);
                        }
    
                        $randomusers[] = $UsersIds[$index];
                    }
    
                    for($i = 0; $i < 10; $i++) {
                        $BelongsTo = new BelongsTo();

                        $BelongsTo->setFk_user($randomusers[$i]);
                        $BelongsTo->setFk_team($Team->getRowid());

                        $BelongsTo->create();
                    }
                }
            }
        }

        $fk_organization = $a+1;
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

        // create tasks

        for($i = 0; $i < 1000; $i++) {
      
            $Task = new Task();

            $sql = "SELECT MIN(mc.rowid) AS minrowid, MAX(mc.rowid) AS maxrowid 
            FROM storieshelper_map_column AS mc 
            INNER JOIN storieshelper_team AS t ON t.rowid = mc.fk_team
            INNER JOIN storieshelper_project AS p ON p.rowid = t.fk_project
            INNER JOIN storieshelper_organization AS o ON o.rowid = p.fk_organization
            WHERE o.rowid = 1;";

            $PDO = new PDO('mysql:host=localhost;dbname=storieshelper;charset=UTF8', 'root');
            $requete = $PDO->prepare($sql);
            $requete->execute();

            $obj = $requete->fetch(PDO::FETCH_OBJ);

            $minrowid = $obj->minrowid;
            $maxrowid = $obj->maxrowid;


            $fk_column = $faker->numberBetween($minrowid, $maxrowid);
            $Task->setFk_column($fk_column);
    
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

            $taskid = $Task->fetch_last_insert_id();


            // Tasks Comments Creation
            for($y = 0; $y < 5; $y++) {
                $TaskComment = new TaskComment();

                $TaskComment->setFk_task($taskid);
                $TaskComment->setNote($faker->sentence());
                $TaskComment->setFk_user($usersTab[$faker->numberBetween(min(array_keys($usersTab)), max(array_keys($usersTab)))]);

                $TaskComment->create();
            }

            // tasks members creation
            for($y = 0; $y < 2; $y++) {
                $TaskMember = new TaskMember();

                $TaskMember->setFk_task($taskid);
                $TaskMember->setFk_user($usersTab[$faker->numberBetween(min(array_keys($usersTab)), max(array_keys($usersTab)))]);
                
                $TaskMember->create();
            }
        }
    }
} catch (\Throwable $th) {
    print $th;
}

?>