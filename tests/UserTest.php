<?php
require_once 'services/header.php';

use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{ 
    public $userId;

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

        //create a new user object
        $User = new User();

        $User->setLastname('Couscous');
        $User->setFirstname('Taboulé');
        $User->setBirth('1956-05-23');
        $User->setPassword('pA$$0rWd');
        $User->setEmail('test@email.com');
        $User->setFk_organization($Organization->getRowid());
        $User->setConsent(1);
        $User->setAdmin(0);
        
        //insert the user into the database
        $lastRowid = $User->create();
        $User->setRowid($lastRowid);

        $this->assertIsInt($lastRowid, 'the user rowid is incorrect');

        $TestUser = new User($lastRowid);

        //verify that the user fetched matches the user object
        $this->assertNotNull($TestUser->getRowid());
        $this->assertSame($User->getFirstname(), $TestUser->getFirstname());
        $this->assertSame($User->getLastname(), $TestUser->getLastname());
        $this->assertSame($User->getBirth(), $TestUser->getBirth());
        $this->assertSame($User->getEmail(), $TestUser->getEmail());
        $this->assertSame($User->getFk_organization(), $TestUser->getFk_organization());
        $this->assertSame($User->getConsent(), $TestUser->getConsent());
        $this->assertSame($User->isAdmin(), $TestUser->isAdmin());

        $User->delete();
    }

    public function testFetch()
    {
        // create an organization
        $Organization = $this->initialize();

        // create a new user object
        $User = new User();

        $User->setLastname('Couscous');
        $User->setFirstname('Taboulé');
        $User->setBirth('1956-05-23');
        $User->setPassword('pA$$0rWd');
        $User->setEmail('test@email.com');
        $User->setFk_organization($Organization->getRowid());
        $User->setConsent(1);
        $User->setAdmin(0);
        
        // insert the user into the database
        $lastRowid = $User->create();
        $User->setRowid($lastRowid);

        $TestUser = new User();
        $TestUser->fetch($lastRowid);

        //verify that the user fetched matches the user object
        $this->assertNotNull($User->getRowid());
        $this->assertSame($User->getFirstname(), $TestUser->getFirstname());
        $this->assertSame($User->getLastname(), $TestUser->getLastname());
        $this->assertSame($User->getBirth(), $TestUser->getBirth());
        $this->assertSame($User->getEmail(), $TestUser->getEmail());
        $this->assertSame($User->getFk_organization(), $TestUser->getFk_organization());
        $this->assertSame($User->getConsent(), $TestUser->getConsent());
        $this->assertSame($User->isAdmin(), $TestUser->isAdmin());

        $User->delete();
    }

    public function testDelete()
    {
        // create an organization
        $Organization = $this->initialize();

        //create a new user object
        $User = new User();

        $User->setLastname('Couscous');
        $User->setFirstname('Taboulé');
        $User->setBirth('1956-05-23');
        $User->setPassword('pA$$0rWd');
        $User->setEmail('test@email.com');
        $User->setFk_organization($Organization->getRowid());
        $User->setConsent(1);
        $User->setAdmin(0);

        $rowid = $User->create();
        $User->setRowid($rowid);

        //delete a user from the database
        $User->delete();

        $TestUser = new User($rowid);

        $this->assertNull($TestUser->getRowid());
    }

    public function testCheckByEmailTrue()
    {
        // create an organization
        $Organization = $this->initialize();

        $User = new User();

        $User->setLastname('Couscous');
        $User->setFirstname('Taboulé');
        $User->setBirth('1956-05-23');
        $User->setPassword('pA$$0rWd');
        $User->setEmail('email@impossible.com');
        $User->setFk_organization($Organization->getRowid());
        $User->setConsent(1);
        $User->setAdmin(0);

        $rowid = $User->create();
        
        $User->setRowid($rowid);

        $TestUser = new User();
        $TestUser->setEmail('email@impossible.com');

        $result = $TestUser->checkByEmail();
        $this->assertTrue($result);

        $User->delete();
    }

    public function testCheckByEmailFalse()
    {
        $User = new User();
        $User->setEmail('email@impossible.com');
        $result = $User->checkByEmail();
        
        $this->assertFalse($result);
    }

    public function testCheckTokenTrue()
    {
        // create an organization
        $Organization = $this->initialize();

        // create user
        $User = new User();
        
        $User->setLastname('Couscous');
        $User->setFirstname('Taboulé');
        $User->setBirth('1956-05-23');
        $User->setPassword('pA$$0rWd');
        $User->setEmail('email@impossible.com');
        $User->setFk_organization($Organization->setRowid());
        $User->setConsent(1);
        $User->setAdmin(0);

        // insert in db
        $lastRowid = $User->create();

        $token = bin2hex(random_bytes(15));
        
        $User->setToken($token);
        $User->setRowid($lastRowid);

        // update in db
        $User->updateToken();

        // check token
        $result = $User->checkToken($lastRowid, $token);

        $this->assertTrue($result);
        // delete in db
        $User->delete();
    }

    public function testCheckTokenFalse()
    {
        // create an organization
        $Organization = $this->initialize();

        // create user
        $User = new User();
        
        $User->setLastname('Couscous');
        $User->setFirstname('Taboulé');
        $User->setBirth('1956-05-23');
        $User->setPassword('pA$$0rWd');
        $User->setEmail('email@impossible.com');
        $User->setFk_organization($Organization->getRowid());
        $User->setConsent(1);
        $User->setAdmin(0);

        // insert in db
        $lastRowid = $User->create();

        $token = $lastRowid . "-" . bin2hex(random_bytes(15));
        $User->setToken($token);

        // update in db
        $User->updateToken();

        // check token
        $result = $User->checkToken($lastRowid, $token . 'HACKED');

        $this->assertFalse($result);
        // delete in db
        $User->delete();
    }

    public function testUpdate()
    {
        // create an organization
        $Organization = $this->initialize();

        // create user
        $User = new User();

        $User->setLastname('Couscous');
        $User->setFirstname('Taboulé');
        $User->setBirth('1956-05-23');
        $User->setPassword('pA$$0rWd');
        $User->setEmail('email@impossible.com');
        $User->setFk_organization($Organization->getRowid());
        $User->setConsent(0);
        $User->setAdmin(0);

        // insert in db
        $lastRowid = $User->create();

        // modify all properties
        $User->setRowid($lastRowid);
        $User->setLastname('Tacos');
        $User->setFirstname('Pastillas');
        $User->setBirth('1968-02-16');
        $User->setPassword('motdepasse');
        $User->setEmail('email@peuprobable.com');
        $User->setFk_organization(1);
        $User->setConsent(1);
        $User->setAdmin(1);

        // update in db
        $User->update();

        $TestUser = new User();
        $TestUser->fetch($lastRowid);

        $this->assertSame('Tacos', $TestUser->getLastname());
        $this->assertSame('Pastillas', $TestUser->getFirstname());
        $this->assertSame('1968-02-16', $TestUser->getBirth());
        $this->assertSame(password_hash('motdepasse', PASSWORD_BCRYPT), $TestUser->getPassword());
        $this->assertSame('email@peuprobable.com', $TestUser->getEmail());
        $this->assertSame(1, $TestUser->getFk_organization());
        $this->assertSame(1, $TestUser->getConsent());
        $this->assertSame(1, $TestUser->isAdmin());

        // delete in db
        $User->delete();
    }
}