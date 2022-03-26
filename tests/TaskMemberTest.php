<?php
require_once 'services/header.php';

use PHPUnit\Framework\TestCase;

/***********************************************
 * To Test, write  "vendor/bin/phpunit tests/" *
 * in the windows shell at the project root    *
 ***********************************************/

class TaskMemberTest extends TestCase
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

        $Project->setRowid($lastIdProject);

        $Team = new Team();
        $Team->setName("name");
        $Team->setFk_project($lastIdProject);

        $lastIdTeam = $Team->create();

        $Team->fetch($lastIdTeam);

        // create a new user object
        $User = new User();

        $User->setFirstname('Taboulé');
        $User->setLastname('Couscous');
        $User->setBirth('1956-05-23');
        $User->setPassword('pA$$0rWd');
        $User->setEmail('test@email.com');
        $User->setFk_organization($Organization->getRowid());
        $User->setConsent(1);
        $User->setAdmin(0);
        
        // insert the user into the database
        $lastIdUser = $User->create();
        $User->setRowid($lastIdUser);

        // create a task
        $Task = new Task();

        // to get columns
        $Team->fetch($lastIdTeam);

        $Task->setFk_column($Team->getMapColumns()[0]->getRowid());
        $Task->setFk_user($User->getRowid());
        $Task->setActive(1);

        $Task->create();

        $lastIdTask = $Task->fetch_last_insert_id();
        $Task->setRowid($lastIdTask);

        // update task object
        $Task->fetch($lastIdTask);

        // $this->name,
        // $this->description,
        // $this->fk_column,
        // $this->rank,
        // $this->active,
        // $this->finished_at,
        // $this->created_at,
        // $this->rowid

        // update Organization
        $Organization = new Organization($lastIdOrganization);

        return $Organization;
    }

    public function testCreate()
    {
        $Organization = $this->initialize();

        // get the user
        $User = $Organization->getUsers()[0];

        // get the task
        $Project    = $Organization->getProjects()[0];
        $Team       = $Project->getTeams()[0];
        $Column     = $Team->getMapColumns()[0];
        $Task       = $Column->getTasks()[0]; 

        // create
        $TaskMember = new TaskMember();

        $TaskMember->setFk_user($User->getRowid());
        $TaskMember->setFk_task($Task->getRowid());

        $TaskMember->create();

        // test
        $TestTaskMember = new TaskMember($User->getRowid(), $Task->getRowid());

        $this->assertNotNull($TestTaskMember->getFk_user());
        $this->assertNotNull($TestTaskMember->getFk_task());

        // delete   
        $Organization->delete();
    }

    public function testDelete()
    {
        $Organization = $this->initialize();

        // get the user
        $User = $Organization->getUsers()[0];

        // get the task
        $Project    = $Organization->getProjects()[0];
        $Team       = $Project->getTeams()[0];
        $Column     = $Team->getMapColumns()[0];
        $Task       = $Column->getTasks()[0]; 

        // create
        $TaskMember = new TaskMember();

        $TaskMember->setFk_user($User->getRowid());
        $TaskMember->setFk_task($Task->getRowid());

        $TaskMember->create();
        
        $TaskMember->delete();

        $TestTaskMember = new TaskMember($User->getRowid(), $Task->getRowid());

        $this->assertNull($TestTaskMember->getFk_user());
        $this->assertNull($TestTaskMember->getFk_task());

        $Organization->delete();
    }

}