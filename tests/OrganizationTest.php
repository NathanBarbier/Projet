<?php
require_once 'services/header.php';

use PHPUnit\Framework\TestCase;

/***********************************************
 * To Test, write  "vendor/bin/phpunit tests/" *
 * in the windows shell at the project root    *
 ***********************************************/

class OrganizationTest extends TestCase
{

    public function testCreate()
    {
        // create an organization
        $Organization = new Organization();
        $Organization->setName('org');
        $lastrowid = $Organization->create();
        $Organization->setRowid($lastrowid);

        $TestOrganization = new Organization($lastrowid);

        $this->assertSame($Organization->getName(), $TestOrganization->getName());
        $this->assertSame($lastrowid, $TestOrganization->getRowid());

        // delete in db
        $Organization->delete();
    }

    public function testFetchUsers()
    {
        // create an organization
        $Organization = new Organization();
        $Organization->setName('org');
        $lastrowid = $Organization->create();
        $Organization->setRowid($lastrowid);

        //create a new user object
        $User = new User();

        $User->setFirstname('Taboulé');
        $User->setLastname('Couscous');
        $User->setBirth('1956-05-23');
        $User->setPassword('pA$$0rWd');
        $User->setEmail('test@email.com');
        $User->setFk_organization($lastrowid);
        $User->setConsent(1);
        $User->setAdmin(0);
        
        //insert the user into the database
        $lastRowid = $User->create();

        // fetch users
        $Organization->fetchUsers();

        $TestUser = $Organization->getUsers()[0];

        $this->assertSame($User->getFirstname(), $TestUser->getFirstname());
        $this->assertSame($User->getLastname(), $TestUser->getLastname());
        $this->assertSame($User->getBirth(), $TestUser->getBirth());
        $this->assertSame($User->getPassword(), $TestUser->getPassword());
        $this->assertSame($User->getEmail(), $TestUser->getEmail());
        $this->assertSame($User->getFk_organization(), $TestUser->getFk_organization());
        $this->assertSame($User->getConsent(), $TestUser->getConsent());
        $this->assertSame($User->isAdmin(), $TestUser->isAdmin());

        // delete in db
        $Organization->delete();
    }

    public function testUpdate()
    {
        // create an organization
        $Organization = new Organization();
        $Organization->setName('org');
        $lastrowid = $Organization->create();
        $Organization->setRowid($lastrowid);

        // update Organization
        // the name is different
        $Organization->setName('cb5ee0b4fd79968b56a718340c0a1514ece9ec412dfc0cecc0d0ebd96a5758849aa0fdd601cf4ca3cbb82314376944ab');
        $Organization->update();

        $TestOrganization = new Organization($lastrowid);

        $this->assertSame('cb5ee0b4fd79968b56a718340c0a1514ece9ec412dfc0cecc0d0ebd96a5758849aa0fdd601cf4ca3cbb82314376944ab', $TestOrganization->getName());

        // delete in db
        $Organization->delete();
    }

    public function testDelete()
    {
        // create an organization
        $Organization = new Organization();
        $Organization->setName('org');
        $lastrowid = $Organization->create();
        $Organization->setRowid($lastrowid);

        // delete
        $Organization->delete();

        // check if the organization still exist
        $TestOrganization = new Organization();
        $TestOrganization->fetch($lastrowid);

        $this->assertNull($TestOrganization->getRowid());
    }

    public function testFetchProjects()
    {
        // create an organization
        $Organization = new Organization();
        $Organization->setName('org');
        $lastrowid = $Organization->create();
        $Organization->setRowid($lastrowid);

        // create a project
        $Project = new Project();
        $Project->setName("name");
        $Project->setType("type");
        $Project->setDescription("description");
        $Project->setFk_organization($lastrowid);

        $projectId = $Project->create();

        $Project->setRowid($projectId);

        // check if the project has been fetched correctly
        $Organization->fetchProjects(0);
        $TestProject = $Organization->getProjects()[0];

        $this->assertSame($Project->getName(), $TestProject->getName());
        $this->assertSame($Project->getType(), $TestProject->getType());
        $this->assertSame($Project->getDescription(), $TestProject->getDescription());
        $this->assertSame($Project->getFk_organization(), $TestProject->getFk_organization());
        
        // delete
        $Organization->delete();
    }

    public function testFetchLogs()
    {
        // create an organization
        $Organization = new Organization();
        $Organization->setName('org');
        $organizationId = $Organization->create();
        $Organization->setRowid($organizationId);

        // create a log entry
    
        LogHistory::create($organizationId, 15, 'INFO', 'action', 'object_type', 'object_name');

        // check if the log has been fetched correctly
        $Organization->fetchLogs();
        $TestLog = $Organization->getLogs()[0];

        $this->assertSame($organizationId, $TestLog->getfk_organization());
        $this->assertSame(15, $TestLog->getfk_author());
        $this->assertSame('INFO', $TestLog->getStatus());
        $this->assertSame('action', $TestLog->getAction());
        $this->assertSame('object_type', $TestLog->getObject_type());
        $this->assertSame('object_name', $TestLog->getObject_name());

        //delete
        $TestLog->delete();
        $Organization->delete();
    }

    public function testFetchUser()
    {
        // create an organization
        $Organization = new Organization();
        $Organization->setName('org');
        $organizationId = $Organization->create();
        $Organization->setRowid($organizationId);

        //create a new user object
        $User = new User();

        $User->setFirstname('Taboulé');
        $User->setLastname('Couscous');
        $User->setBirth('1956-05-23');
        $User->setPassword('pA$$0rWd');
        $User->setEmail('test@email.com');
        $User->setFk_organization($organizationId);
        $User->setConsent(1);
        $User->setAdmin(0);
    
        //insert the user into the database
        $userId = $User->create();
        $User->setRowid($userId);
        $Organization->addUser($User);
        
        $TestUser = $Organization->getUser($userId);

        $this->assertNotFalse($TestUser);
        $this->assertSame($User->getFirstname(), $TestUser->getFirstname());
        $this->assertSame($User->getLastname(), $TestUser->getLastname());
        $this->assertSame($User->getBirth(), $TestUser->getBirth());
        $this->assertSame($User->getPassword(), $TestUser->getPassword());
        $this->assertSame($User->getEmail(), $TestUser->getEmail());
        $this->assertSame($User->getFk_organization(), $TestUser->getFk_organization());
        $this->assertSame($User->getConsent(), $TestUser->getConsent());
        $this->assertSame($User->isAdmin(), $TestUser->isAdmin());

        // delete
        $Organization->delete();
    }

    public function testCheckByNameTrue()
    {
        // create an organization
        $Organization = new Organization();
        $Organization->setName('org');
        $organizationId = $Organization->create();
        $Organization->setRowid($organizationId);

        // test
        $this->assertTrue($Organization->checkByName($Organization->getName()));

        // delete
        $Organization->delete();
    }

    public function testCheckByNameFalse()
    {
        // create an organization
        $Organization = new Organization();
        $Organization->setName('org');
        $organizationId = $Organization->create();
        $Organization->setRowid($organizationId);

        // test
        $this->assertFalse($Organization->checkByName('gro'));

        // delete
        $Organization->delete();
    }

    public function testCheckProjectTrue()
    {
        // create an organization
        $Organization = new Organization();
        $Organization->setName('org');
        $lastrowid = $Organization->create();
        $Organization->setRowid($lastrowid);

        // create a project
        $Project = new Project();
        $Project->setName("name");
        $Project->setType("type");
        $Project->setDescription("description");
        $Project->setFk_organization($lastrowid);

        $projectId = $Project->create();
        $Project->setRowid($projectId);

        $Organization->addProject($Project);

        // test
        $this->assertTrue($Organization->checkProject($projectId));

        // delete
        $Organization->delete();
    }

    public function testCheckProjectFalse()
    {
        // create an organization
        $Organization = new Organization();
        $Organization->setName('org');
        $lastrowid = $Organization->create();
        $Organization->setRowid($lastrowid);

        // test
        $this->assertFalse($Organization->checkProject(15));

        // delete
        $Organization->delete();
    }

}