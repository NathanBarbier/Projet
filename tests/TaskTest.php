<?php
require_once "models/Modele.php";
require_once "services/header.php";

use PHPUnit\Framework\TestCase;

class TaskTest extends TestCase
{
    private function initialize()
    {
        $Organization = new Organization();
        $Organization->setName("org");
        $Organization->create();

        $lastIdOrganization = $Organization->fetch_last_insert_id();
        $Organization->setRowid($lastIdOrganization);

        $Project = new Project();
        $Project->setName("name");
        $Project->setType("type");
        $Project->setDescription("description");
        $Project->setFk_organization($lastIdOrganization);

        $lastIdProject = $Project->create();

        $Team = new Team();
        $Team->setName("name");
        $Team->setFk_project($lastIdProject);

        $lastIdTeam = $Team->create();

        $Team->fetch($lastIdTeam);

        $Columns = $Team->getMapColumns();

        foreach($Columns as $key => $Column)
        {
            if ($key == 0)
            {
                $idColumn = $Column->getRowid();
            }
        }

        return [
            "idColumn" => $idColumn,
            "Organization" => $Organization
        ];
    }

    public function testCreate()
    {
        $idColumn = $this->initialize()["idColumn"];

        $Task = new Task();
        $Task->setFk_column($idColumn);
        $Task->setFk_user(0);
        $Task->setActive(1);
        $Task->create();

        $lastId = intval($Task->fetch_last_insert_id());

        $testTask = new Task($lastId);

        $this->assertSame($testTask->getFk_column(),(int) $idColumn);
        $this->assertSame($testTask->getFk_user(), 0);
        $this->assertSame($testTask->isActive(), 1);

        $testTask->delete();
    }

    public function testUpdate()
    {
        $idColumn = $this->initialize()["idColumn"];

        $Task = new Task();
        $Task->setFk_column($idColumn);
        $Task->setFk_user(0);
        $Task->setActive(1);
        $Task->create();

        $lastId = intval($Task->fetch_last_insert_id());

        $updateTask = new Task($lastId);

        $updateTask->setActive(0);
        $updateTask->setName("name");
        $updateTask->setRank(25);
        $updateTask->setFinished_at("2022-03-15 16:43:25");
        $updateTask->update();

        $testTask = new Task($lastId);

        $this->assertSame($testTask->isActive(), 0);
        $this->assertSame($testTask->getName(), "name");
        $this->assertSame($testTask->getRank(), 25);
        $this->assertSame($testTask->getFinished_at(), "2022-03-15 16:43:25");

        $testTask->delete();
    }

    public function testDelete()
    {
        $idColumn = $this->initialize()["idColumn"];

        $Task = new Task();
        $Task->setFk_column($idColumn);
        $Task->setFk_user(0);
        $Task->setActive(1);
        $Task->create();

        $lastId = intval($Task->fetch_last_insert_id());
        $Task->setRowid($lastId);

        $Task->delete();

        $testTask = new Task($lastId);

        $this->assertNull($testTask->getRowid());
    }

    public function testFetchNextRankTrue()
    {
        $idColumn = $this->initialize()["idColumn"];

        $Task1 = new Task();
        $Task1->setFk_column($idColumn);
        $Task1->setFk_user(0);
        $Task1->setActive(1);
        $Task1->create();

        $lastId = intval($Task1->fetch_last_insert_id());

        $testTask1 = new Task($lastId);
        $testTask1->setRank(100);
        $testTask1->update();

        $Task2 = new Task();
        $Task2->setFk_column($idColumn);
        $Task2->setFk_user(0);
        $Task2->setActive(1);
        $Task2->create();
        
        $lastId = intval($Task2->fetch_last_insert_id());

        $testTask2 = new Task($lastId);
        $testTask2->setRank(0);
        $testTask2->update();

        $result = $Task2->fetchNextRank($testTask2->getRowid(), $idColumn)->nextRank;

        $testTask1->delete();
        $testTask2->delete();

        $this->assertSame("100", $result);
    }

    public function testFetchNextRankFalse()
    {
        $idColumn = $this->initialize()["idColumn"];

        $Task1 = new Task();
        $Task1->setFk_column($idColumn);
        $Task1->setFk_user(0);
        $Task1->setActive(1);
        $Task1->create();

        $lastId = intval($Task1->fetch_last_insert_id());

        $testTask1 = new Task($lastId);
        $testTask1->setRank(0);
        $testTask1->update();

        $Task2 = new Task();
        $Task2->setFk_column($idColumn);
        $Task2->setFk_user(0);
        $Task2->setActive(1);
        $Task2->create();
        
        $lastId = intval($Task2->fetch_last_insert_id());

        $testTask2 = new Task($lastId);
        $testTask2->setRank(100);
        $testTask2->update();

        $result = $Task2->fetchNextRank($testTask2->getRowid(), $idColumn);

        $testTask1->delete();
        $testTask2->delete();

        $this->assertFalse($result);
    }
    
    public function testFetchPrevRankTrue()
    {
        $idColumn = $this->initialize()["idColumn"];

        $Task1 = new Task();
        $Task1->setFk_column($idColumn);
        $Task1->setFk_user(0);
        $Task1->setActive(1);
        $Task1->create();

        $lastId = intval($Task1->fetch_last_insert_id());

        $testTask1 = new Task($lastId);
        $testTask1->setRank(0);
        $testTask1->update();

        $Task2 = new Task();
        $Task2->setFk_column($idColumn);
        $Task2->setFk_user(0);
        $Task2->setActive(1);
        $Task2->create();
        
        $lastId = intval($Task2->fetch_last_insert_id());

        $testTask2 = new Task($lastId);
        $testTask2->setRank(100);
        $testTask2->update();

        $result = $Task2->fetchPrevRank($testTask2->getRowid(), $idColumn)->prevRank;

        $testTask1->delete();
        $testTask2->delete();

        $this->assertSame("0", $result);
    }

    public function testFetchPrevRankFalse()
    {
        $idColumn = $this->initialize()["idColumn"];

        $Task1 = new Task();
        $Task1->setFk_column($idColumn);
        $Task1->setFk_user(0);
        $Task1->setActive(1);
        $Task1->create();

        $lastId = intval($Task1->fetch_last_insert_id());

        $testTask1 = new Task($lastId);
        $testTask1->setRank(2);
        $testTask1->update();

        $Task2 = new Task();
        $Task2->setFk_column($idColumn);
        $Task2->setFk_user(0);
        $Task2->setActive(1);
        $Task2->create();
        
        $lastId = intval($Task2->fetch_last_insert_id());

        $testTask2 = new Task($lastId);
        $testTask2->setRank(0);
        $testTask2->update();

        $result = $Task2->fetchPrevRank($testTask2->getRowid(), $idColumn);

        $testTask1->delete();
        $testTask2->delete();

        $this->assertFalse($result);
    }

    
    public function testSwitchRank()
    {
        $idColumn = $this->initialize()["idColumn"];
        $Organization = $this->initialize()["Organization"];
        
        $Task1 = new Task();
        $Task1->setFk_column($idColumn);
        $Task1->setFk_user(0);
        $Task1->setActive(1);
        $Task1->create();

        $lastId = intval($Task1->fetch_last_insert_id());

        $testTask1 = new Task($lastId);
        $testTask1->setRank(100);
        $testTask1->setName("tache 1");
        $testTask1->update();

        $Task2 = new Task();
        $Task2->setFk_column($idColumn);
        $Task2->setFk_user(0);
        $Task2->setActive(1);
        $Task2->create();
        
        $lastId = intval($Task2->fetch_last_insert_id());

        $testTask2 = new Task($lastId);
        $testTask2->setRank(0);
        $testTask2->setName("tache 2");
        $testTask2->update();

        $testTask2->switchRank($testTask2->getRowid(), $idColumn, "up");
        
        $testTask1->fetch($testTask1->getRowid());
        $testTask2->fetch($testTask2->getRowid());
        
        $this->assertSame(100, $testTask2->getRank());
        $this->assertSame(0, $testTask1->getRank());
        
        $testTask1->delete();
        $testTask2->delete();
        $Organization->delete();
    }
}
?>
