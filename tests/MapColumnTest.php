<?php 
require_once 'services/header.php';

use PHPUnit\Framework\TestCase;

/***********************************************
 * To Test, write  "vendor/bin/phpunit tests/" *
 * in the windows shell at the project root    *
 ***********************************************/

class MapColumnTest extends TestCase
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

        // get team
        $Project    = $Organization->getProjects()[0];
        $Team       = $Project->getTeams()[0]; 

        // create column
        $MapColumn = new MapColumn();

        $MapColumn->setName('unnomdecolonneimprobable');
        $MapColumn->setFk_team($Team->getRowid());

        $lastrowid = $MapColumn->create();

        $TestMapColumn = new MapColumn($lastrowid);

        $this->assertSame($TestMapColumn->getRowid(), $lastrowid);
        $this->assertSame($TestMapColumn->getName(), 'unnomdecolonneimprobable');

        $Organization->delete();
    }

    public function testDelete()
    {
        $Organization = $this->initialize();

        // get team
        $Project    = $Organization->getProjects()[0];
        $Team       = $Project->getTeams()[0]; 

        // create column
        $MapColumn = new MapColumn();

        $MapColumn->setName('unnomdecolonneimprobable');
        $MapColumn->setFk_team($Team->getRowid());

        $lastrowid = $MapColumn->create();
        $MapColumn->setRowid($lastrowid);

        // delete
        $MapColumn->delete();

        // test
        $TestMapColumn = new MapColumn($lastrowid);
        
        $this->assertNull($TestMapColumn->getRowid());
        
        $Organization->delete();
    }
}

?>