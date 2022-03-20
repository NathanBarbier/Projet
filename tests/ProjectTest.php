<?php
require_once 'services/header.php';

use PHPUnit\Framework\TestCase;

class ProjectTest extends TestCase
{
    private function initialize()
    {
        $Organization = new Organization();
        $Organization->setName("org");
        $Organization->create();
        $lastIdOrganization = $Organization->fetch_last_insert_id();
        $Organization->setRowid($lastIdOrganization);
        
        return $Organization;
    }

    public function testCreate()
    {
        // create an organization
        $Organization = $this->initialize();

        //create a new project
        $Project = new Project();

        $Project->setName('cb5ee0b4fd79968b56a718340');
        $Project->setType('601cf4ca3cbb82314376944ab');
        $Project->setDescription('dfc0cecc0d0ebd96a5758849aa');
        $Project->setFk_organization($Organization->getRowid());

        $lastrowid = $Project->create();

        $TestProject = new Project($lastrowid);

        $this->assertSame($Project->getName(), $TestProject->getName());
        $this->assertSame($Project->getType(), $TestProject->getType());
        $this->assertSame($Project->getDescription(), $TestProject->getDescription());
        $this->assertSame($Project->getFk_organization(), $TestProject->getFk_organization());

        //delete
        $Organization->delete();
    }

    public function testUpdate()
    {
        // create an organization
        $Organization = $this->initialize();

        //create a new project
        $Project = new Project();

        $Project->setName('cb5ee0b4fd79968b56a718340');
        $Project->setType('601cf4ca3cbb82314376944ab');
        $Project->setDescription('dfc0cecc0d0ebd96a5758849aa');
        $Project->setFk_organization($Organization->getRowid());

        $lastrowid = $Project->create();
        $Project->setRowid($lastrowid);

        // update the project
        $Project->setName('unnomdeprojetinexistant');
        $Project->setType('untypedeprojetinexistant');
        $Project->setDescription('unedescriptiondeprojetinexistante');
        $Project->setFk_organization($Organization->getRowid());

        $Project->update();

        // test
        $TestProject = new Project($lastrowid);

        $this->assertSame('unnomdeprojetinexistant', $TestProject->getName());
        $this->assertSame('untypedeprojetinexistant', $TestProject->getType());
        $this->assertSame('unedescriptiondeprojetinexistante', $TestProject->getDescription());
        $this->assertSame($Organization->getRowid(), $TestProject->getFk_organization());

        //delete
        $Organization->delete();
    }

    public function testDelete()
    {
        // create an organization
        $Organization = $this->initialize();

        //create a new project
        $Project = new Project();

        $Project->setName('cb5ee0b4fd79968b56a718340');
        $Project->setType('601cf4ca3cbb82314376944ab');
        $Project->setDescription('dfc0cecc0d0ebd96a5758849aa');
        $Project->setFk_organization($Organization->getRowid());

        $lastrowid = $Project->create();
        $Project->setRowid($lastrowid);

        // delete
        $Project->delete();

        // check if the project still exist
        $TestProject = new Project();
        $TestProject->fetch($lastrowid);

        $this->assertNull($TestProject->getRowid());

        // delete
        $Organization->delete();
    }

    public function testFetchTeams()
    {
        // create an organization
        $Organization = $this->initialize();

        //create a new project
        $Project = new Project();

        $Project->setName('cb5ee0b4fd79968b56a718340');
        $Project->setType('601cf4ca3cbb82314376944ab');
        $Project->setDescription('dfc0cecc0d0ebd96a5758849aa');
        $Project->setFk_organization($Organization->getRowid());

        $projectId = $Project->create();
        $Project->setRowid($projectId);

        // create a team
        $Team = new Team();

        $Team->setName('unnomdeteamprobablementinexistant');
        $Team->setFk_project($projectId);

        $teamId = $Team->create();

        // fetch teams
        $Project->fetchTeams();
        
        $TestTeam = $Project->getTeams()[0]; 

        // compare values
        $this->assertSame($TestTeam->getName(), $Team->getName());
        $this->assertSame($TestTeam->getFk_project(), $Team->getFk_project());

        // delete
        $Organization->delete();
    }

    public function testCheckTeamTrue()
    {
        // create an organization
        $Organization = $this->initialize();

        //create a new project
        $Project = new Project();

        $Project->setName('cb5ee0b4fd79968b56a718340');
        $Project->setType('601cf4ca3cbb82314376944ab');
        $Project->setDescription('dfc0cecc0d0ebd96a5758849aa');
        $Project->setFk_organization($Organization->getRowid());

        $projectId = $Project->create();
        $Project->setRowid($projectId);

        // create a team
        $Team = new Team();

        $Team->setName('unnomdeteamprobablementinexistant');
        $Team->setFk_project($projectId);

        $teamId = $Team->create();
        $Team->setRowid($teamId);

        $Project->addTeam($Team);

        // test
        $this->assertTrue($Project->checkTeam($teamId));

        // delete
        $Organization->delete();
    }

    public function testCheckTeamFalse()
    {
        // create an organization
        $Organization = $this->initialize();

        //create a new project
        $Project = new Project();

        $Project->setName('cb5ee0b4fd79968b56a718340');
        $Project->setType('601cf4ca3cbb82314376944ab');
        $Project->setDescription('dfc0cecc0d0ebd96a5758849aa');
        $Project->setFk_organization($Organization->getRowid());

        $projectId = $Project->create();
        $Project->setRowid($projectId);

        // test
        $this->assertFalse($Project->checkTeam(15));

        // delete
        $Organization->delete();   
    }
}
?>