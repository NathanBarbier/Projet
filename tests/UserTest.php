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

        $User->setFirstname('Taboulé');
        $User->setLastname('Couscous');
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

        // delete in db
        $Organization->delete();
    }

    public function testFetch()
    {
        // create an organization
        $Organization = $this->initialize();

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

        // delete in db
        $Organization->delete();
    }

    public function testDelete()
    {
        // create an organization
        $Organization = $this->initialize();

        //create a new user object
        $User = new User();

        $User->setFirstname('Taboulé');
        $User->setLastname('Couscous');
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

        $Organization->delete();
    }

    public function testCheckByEmailTrue()
    {
        // create an organization
        $Organization = $this->initialize();

        $User = new User();

        $User->setFirstname('Taboulé');
        $User->setLastname('Couscous');
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

        // delete in db
        $Organization->delete();
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
        
        $User->setFirstname('Taboulé');
        $User->setLastname('Couscous');
        $User->setBirth('1956-05-23');
        $User->setPassword('pA$$0rWd');
        $User->setEmail('email@impossible.com');
        $User->setFk_organization($Organization->getRowid());
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
        $Organization->delete();
    }

    public function testCheckTokenFalse()
    {
        // create an organization
        $Organization = $this->initialize();

        // create user
        $User = new User();
        
        $User->setFirstname('Taboulé');
        $User->setLastname('Couscous');
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
        $Organization->delete();
    }

    public function testUpdate()
    {
        // create an organization
        $Organization = $this->initialize();

        // create user
        $User = new User();

        $User->setFirstname('Taboulé');
        $User->setLastname('Couscous');
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
        $User->setFirstname('Pastillas');
        $User->setLastname('Tacos');
        $User->setBirth('1968-02-16');
        $User->setPassword('motdepasse');
        $User->setEmail('email@peuprobable.com');
        $User->setFk_organization($Organization->getRowid());
        $User->setConsent(1);
        $User->setAdmin(1);

        // update in db
        $User->update();

        $TestUser = new User();
        $TestUser->fetch($lastRowid);

        $this->assertSame('Pastillas', $TestUser->getFirstname(), "`firstname` has not been updated");
        $this->assertSame('Tacos', $TestUser->getLastname(), "`lastname` has not been updated");
        $this->assertSame('1968-02-16', $TestUser->getBirth(), "`birth` has not been updated");
        $this->assertTrue(password_verify('motdepasse', $TestUser->getPassword()), "`password` has not been updated");
        $this->assertSame('email@peuprobable.com', $TestUser->getEmail(), "`email` has not been updated");
        $this->assertSame($Organization->getRowid(), $TestUser->getFk_organization(), "`fk_organization` has not been updated");
        $this->assertSame(1, $TestUser->getConsent(), "`consent` has not been updated");
        $this->assertSame(1, $TestUser->isAdmin(), "`admin` has not been updated");

        // delete in db
        $Organization->delete();
    }

    public function testInitializePrivacyZero()
    {
        // create an organization
        $Organization = $this->initialize();

        // create user
        $User = new User();

        $User->setFirstname('Taboulé');
        $User->setLastname('Couscous');
        $User->setBirth('1956-05-23');
        $User->setPassword('pA$$0rWd');
        $User->setEmail('email@impossible.com');
        $User->setFk_organization($Organization->getRowid());
        $User->setConsent(0);
        $User->setAdmin(0);

        // insert in db
        $lastUserId = $User->create();

        $TestOrganization = new Organization();

        $TestOrganization->setRowid($Organization->getRowid());
        // Set privacy to zero
        $TestOrganization->setPrivacy(0);
        $TestOrganization->fetchUsers();

        $TestUser = $TestOrganization->getUsers()[0];

        $this->assertSame($lastUserId, $TestUser->getRowid());
        $this->assertSame('Taboulé', $TestUser->getFirstname());
        $this->assertSame('Couscous', $TestUser->getLastname());
        $this->assertSame($Organization->getRowid(), $TestUser->getFk_organization());
        $this->assertSame(0, $TestUser->isAdmin());
        $this->assertSame($User->getBirth(), $TestUser->getBirth());
        $this->assertSame($User->getPassword(), $TestUser->getPassword());
        $this->assertSame($User->getConsent(), $TestUser->getConsent());
        $this->assertSame($User->getConsentDate(), $TestUser->getConsentDate());
        $this->assertSame($User->getToken(), $TestUser->getToken());
        $this->assertSame($User->getEmail(), $TestUser->getEmail());        

        // delete in db
        $Organization->delete();
    }

    public function testInitializePrivacyOne()
    {
        // create an organization
        $Organization = $this->initialize();

        // create user
        $User = new User();

        $User->setFirstname('Taboulé');
        $User->setLastname('Couscous');
        $User->setBirth('1956-05-23');
        $User->setPassword('pA$$0rWd');
        $User->setEmail('email@impossible.com');
        $User->setFk_organization($Organization->getRowid());
        $User->setConsent(0);
        $User->setAdmin(0);

        // insert in db
        $lastUserId = $User->create();

        $TestOrganization = new Organization();

        $TestOrganization->setRowid($Organization->getRowid());
        // Set privacy to zero
        $TestOrganization->setPrivacy(1);
        $TestOrganization->fetchUsers();

        $TestUser = $TestOrganization->getUsers()[0];

        $this->assertSame($lastUserId, $TestUser->getRowid());
        $this->assertSame('Taboulé', $TestUser->getFirstname());
        $this->assertSame('Couscous', $TestUser->getLastname());
        $this->assertSame($Organization->getRowid(), $TestUser->getFk_organization());
        $this->assertSame(0, $TestUser->isAdmin());
        $this->assertNull($TestUser->getBirth());
        $this->assertNull($TestUser->getPassword());
        $this->assertNull($TestUser->getConsent());
        $this->assertNull($TestUser->getConsentDate());
        $this->assertNull($TestUser->getToken());
        $this->assertSame($User->getEmail(), $TestUser->getEmail());        

        // delete in db
        $Organization->delete();
    }

    public function testInitializePrivacyTwo()
    {
        // create an organization
        $Organization = $this->initialize();

        // create user
        $User = new User();

        $User->setFirstname('Taboulé');
        $User->setLastname('Couscous');
        $User->setBirth('1956-05-23');
        $User->setPassword('pA$$0rWd');
        $User->setEmail('email@impossible.com');
        $User->setFk_organization($Organization->getRowid());
        $User->setConsent(0);
        $User->setAdmin(0);

        // insert in db
        $lastUserId = $User->create();

        $TestOrganization = new Organization();

        $TestOrganization->setRowid($Organization->getRowid());
        // Set privacy to zero
        $TestOrganization->setPrivacy(2);
        $TestOrganization->fetchUsers();

        $TestUser = $TestOrganization->getUsers()[0];

        $this->assertSame($lastUserId, $TestUser->getRowid());
        $this->assertSame('Taboulé', $TestUser->getFirstname());
        $this->assertSame('Couscous', $TestUser->getLastname());
        $this->assertSame($Organization->getRowid(), $TestUser->getFk_organization());
        $this->assertSame(0, $TestUser->isAdmin());
        $this->assertNull($TestUser->getBirth());
        $this->assertNull($TestUser->getPassword());
        $this->assertNull($TestUser->getConsent());
        $this->assertNull($TestUser->getConsentDate());
        $this->assertNull($TestUser->getToken());
        $this->assertNull($TestUser->getEmail());        

        // delete in db
        $Organization->delete();
    }
}