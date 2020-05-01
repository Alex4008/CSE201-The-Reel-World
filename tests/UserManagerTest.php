<?php
use PHPUnit\Framework\TestCase;

// Load passwords
require_once('db.php');

final class UserManagerTest extends TestCase
{
    private $testUsername = 'unittestuser';
    private $testPassword = 'unittestpassword';
    private $testUserId = null;
    
    // Testing if UserManager object can be created 
    public function testCanBeCreated(): void
    {
        $this->assertInstanceOf(
            UserManager::class,
            new UserManager($GLOBALS['mysqli'])
        );
    }
    
    // Testing if login() retrieves correct userName given valid login information 
    public function testCanLogin(): void 
    {
        global $testUsername, $testPassword;
        
        $this->cleanUpTest();
        $this->setUpTest();
        
        $um = new UserManager($GLOBALS['mysqli']);
        $statement = $um->login($testUsername, $testPassword);
        $result = $statement->get_result();
        
        $user = null;
        
        if($row = $result->fetch_assoc()) {   
            $user = $row['userName'];
        }
        
        $this->assertEquals(
            $testUsername,
            $user
        );
        
        $this->cleanUpTest();
    }
    
    // Testing login() when given invalid login information 
    public function testCanLoginFail(): void 
    {
        global $testUsername;
        
        $this->cleanUpTest();
        $this->setUpTest(); 
        
        $um = new UserManager($GLOBALS['mysqli']);
        $statement = $um->login($testUsername, 'badpassword');
        $result = $statement->get_result();
        
        $user = null;
        
        if($row = $result->fetch_assoc()) {
            $user = $row['userName'];
        }
        
        $this->assertEquals(
            null,
            $user
        );
        
        $this->cleanUpTest();
    }
    
    // Testing if signup() adds user to the database 
    public function testCanSignup(): void 
    {
        global $testUsername, $testPassword;
        
        $this->cleanUpTest();
        
        $um = new UserManager($GLOBALS['mysqli']);
        $statement = $um->signup($testUsername, $testPassword, 'testdisplayname');
        
        $sql = "SELECT userName FROM Users WHERE userName = '" . $testUsername . "'";
        $sqlResult = $GLOBALS['mysqli']->query($sql);
        
        $this->assertGreaterThan(
            0,
            $sqlResult->num_rows
        );
        
        $row = $sqlResult->fetch_assoc();
        
        $this->assertEquals(
            $testUsername,
            $row["userName"]
        );
        
        $this->cleanUpTest();
    }
    
    // Testing if checkUsername() given an unused username returns true
    public function testCanCheckUsername(): void 
    {
//        global $testUsername;
//        
//        $this->cleanUpTest();
//        
//        $um = new UserManager($GLOBALS['mysqli']);
//        $statement = $um->checkUsername($testUsername);
//        
//        $this->assertEquals(
//            true,
//            $statement
//        );
//        
//        $this->cleanUpTest();
    }
    
    // Testing if checkUsername() given an existing username returns false
    public function testCanCheckInvalidUsername(): void 
    {
//        global $testUsername;
//        
//        $this->cleanUpTest();
//        $this->setUpTest();
//        
//        $um = new UserManager($GLOBALS['mysqli']);
//        $statement = $um->checkUsername($testUsername);
//        
//        $this->assertEquals(
//            false,
//            $statement
//        );
//        
//        $this->cleanUpTest();
    }
    
    // - - - - - HELPER FUNCTIONS - - - - - 
    // Inserts test user
    public function setUpTest(): void 
    {
        global $testUsername, $testPassword, $testUserId;
        
        $sql = "INSERT INTO Users (userName, password, displayName) VALUES ('" . $testUsername . "', '" . $testPassword . "', 'Testfirst, Testlast')";
        
        if ($GLOBALS['mysqli']->query($sql) === TRUE) {
            $testUserId = $GLOBALS['mysqli']->insert_id;
        }
    }
    
    // Cleans up test code
    public function cleanUpTest(): void 
    {
        global $testUserId; 
        
        // Delete test user
        $sql = "DELETE FROM Users WHERE userId = '" . $testUserId . "'";
        $GLOBALS['mysqli']->query($sql);
    }
}
?>