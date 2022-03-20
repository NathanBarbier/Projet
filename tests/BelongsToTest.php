<?php
require_once 'services/header.php';

use PHPUnit\Framework\TestCase;

class BelongsToTest extends TestCase
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
        $lastRowid = $User->create();
        $User->setRowid($lastRowid);

        // update Organization
        $Organization = new Organization($lastIdOrganization);

        return $Organization;
    }

    public function testCreate()
    {
        $Organization = $this->initialize();

        // fetch the user
        $User = $Organization->getUsers()[0];

        // fetch the team
        $Team = $Organization->getProjects()[0]->getTeams()[0];

        // create
        $BelongsTo = new BelongsTo();

        $BelongsTo->setFk_team($Team->getRowid());
        $BelongsTo->setFk_user($User->getRowid());

        $BelongsTo->create();

        // test
        $TestBelongsTo = new BelongsTo($User->getRowid(), $Team->getRowid());

        $this->assertNotNull($TestBelongsTo->getFk_user());
        $this->assertNotNull($TestBelongsTo->getFk_team());

        // delete   
        $Organization->delete();
    }

    public function testDelete()
    {
        $Organization = $this->initialize();

        // fetch the user
        $User = $Organization->getUsers()[0];

        // fetch the team
        $Team = $Organization->getProjects()[0]->getTeams()[0];

        // create
        $BelongsTo = new BelongsTo();

        $BelongsTo->setFk_user($User->getRowid());
        $BelongsTo->setFk_team($Team->getRowid());

        $BelongsTo->create();

        $BelongsTo->delete();

        $TestBelongsTo = new BelongsTo($User->getRowid(), $Team->getRowid());

        $this->assertNull($TestBelongsTo->getFk_user());
        $this->assertNull($TestBelongsTo->getFk_team());

        $Organization->delete();
    }
}