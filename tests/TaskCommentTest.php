<?php
require_once "services/header.php";

use PHPUnit\Framework\TestCase;

class TaskCommentTest extends TestCase
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

        $Task = new Task();
        $Task->setFk_column($idColumn);
        $Task->setFk_user(0);
        $Task->setActive(1);
        $Task->create();

        return [
            "idTask" => intval($Task->fetch_last_insert_id()),
            "Organization" => $Organization
        ];
    }

    public function testCreate()
    {
        $lastIdTask = $this->initialize()["idTask"];

        $TaskComment = new TaskComment();
        $TaskComment->setFk_task($lastIdTask);
        $TaskComment->setNote("create");
        $TaskComment->setFk_user(0);

        $lastId = $TaskComment->create();

        $testTaskComment = new TaskComment($lastId);

        $this->assertSame($testTaskComment->getFk_task(), $lastIdTask);
        $this->assertSame($testTaskComment->getNote(), "create");
        $this->assertSame($testTaskComment->getFk_user(), 0);
    }

    public function testUpdate()
    {
        $lastIdTask = $this->initialize()["idTask"];

        $TaskComment = new TaskComment();
        $TaskComment->setFk_task($lastIdTask);
        $TaskComment->setNote("create");
        $TaskComment->setFk_user(0);

        $lastId = $TaskComment->create();

        $updateTaskComment = new TaskComment($lastId);

        $updateTaskComment->setNote("update");
        $updateTaskComment->update();

        $testTaskComment = new TaskComment($lastId);

        $this->assertSame($testTaskComment->getNote(), "update");
    }

    public function testDelete()
    {
        $lastIdTask = $this->initialize()["idTask"];
        $Organization = $this->initialize()["Organization"];
        
        $TaskComment = new TaskComment();
        $TaskComment->setFk_task($lastIdTask);
        $TaskComment->setNote("create");
        $TaskComment->setFk_user(0);
        
        $lastId = $TaskComment->create();
        $TaskComment->setRowid($lastId);

        $TaskComment->delete();

        $testTaskComment = new TaskComment($lastId);

        $this->assertNull($testTaskComment->getRowid());
        
        $Organization->delete();
    }
}
?>
