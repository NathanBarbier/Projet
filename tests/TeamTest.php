<?php
require_once "models/Modele.php";
require_once "models/Team.php";

use PHPUnit\Framework\TestCase;

class TeamTest extends TestCase
{
    // public function testCreate()
    // {
    //     $Team = new Team();
    //     $Team->setName("équipe");
    //     $Team->setFk_project(0);
    //     $Team->setActive(1);
    //     $lastId = $Team->create();

    //     $testTeam = new Team($lastId);

    //     $this->assertSame($testTeam->getName(), "équipe");
    //     $this->assertSame($testTeam->getFk_project(), 0);
    //     $this->assertSame($testTeam->isActive(), 1);

    //     $testTeam->delete();
    // }

    // public function testUpdate()
    // {
    //     $Team = new Team();
    //     $Team->setName("équipe");
    //     $Team->setFk_project(0);
    //     $Team->setActive(1);
    //     $lastId = $Team->create();

    //     $testTeam = new Team($lastId);

    //     $updateteam = new Team($lastId);

    //     $updateteam->setActive(0);
    //     $updateteam->setName("name");
    //     $updateteam->setActive(0);
    //     $updateteam->update();

    //     $this->assertSame($updateteam->getName(), "name");
    //     $this->assertSame($updateteam->getFk_project(), 0);
    //     $this->assertSame($testTeam->isActive(), 0);

    //     $testTeam->delete();
    // }
}
