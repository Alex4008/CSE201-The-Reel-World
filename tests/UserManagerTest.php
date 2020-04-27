<?php
use PHPUnit\Framework\TestCase;

// Load passwords
require_once('db.php');

final class UserManagerTest extends TestCase
{
    // Testing if UserManager object can be created 
    public function testCanBeCreated(): void
    {
        $this->assertInstanceOf(
            UserManager::class,
            new UserManager($GLOBALS['mysqli'])
        );
    }
    
    // Testing if login() retrieves correct userName given valid login information 
    // Assumes username 'kcolin0' exists in database and has password 'PkuKTwB'
    public function testCanLogin(): void 
    {
        $mm = new UserManager($GLOBALS['mysqli']);
        $statement = $mm->login('kcolin0', 'PkuKTwB');
        $result = $statement->get_result();
        
        $user = null;
        
        if($row = $result->fetch_assoc()) {   
            $user = $row['userName'];
        }
        
        $this->assertEquals(
            'kcolin0',
            $user
        );
    }
    
    // Testing login() when given invalid login information 
    // Assumes username 'kcolin0' does not have password 'badpassword'
    public function testCanLoginFail(): void 
    {
        $mm = new UserManager($GLOBALS['mysqli']);
        $statement = $mm->login('kcolin0', 'badpassword');
        $result = $statement->get_result();
        
        $user = null;
        
        if($row = $result->fetch_assoc()) {
            $user = $row['userName'];
        }
        
        $this->assertEquals(
            null,
            $user
        );
    }
    
    // Testing if signup() adds user to the database 
    public function testCanSignup(): void 
    {
//        $mm = new UserManager($GLOBALS['mysqli']);
//        $statement = $mm->signup('kcolin0', 'badpassword');
//        $result = $statement->get_result();
//        
//        $user = null;
//        
//        if($row = $result->fetch_assoc()) {
//            $user = $row['userName'];
//        }
//        
//        $this->assertEquals(
//            null,
//            $user
//        );
    }
    
    // Testing if checkUsername() given an existing usernames gets the username ?? 
    public function testCanCheckUsername(): void 
    {
//        $mm = new UserManager($GLOBALS['mysqli']);
//        $statement = $mm->login('kcolin0', 'badpassword');
//        $result = $statement->get_result();
//        
//        $user = null;
//        
//        if($row = $result->fetch_assoc()) {
//            $user = $row['userName'];
//        }
//        
//        $this->assertEquals(
//            null,
//            $user
//        );
    }
    
    // Testing if checkUsername() given an invalid username gives back an error 
    public function testCanCheckInvalidUsername(): void 
    {
//        $mm = new UserManager($GLOBALS['mysqli']);
//        $statement = $mm->login('kcolin0', 'badpassword');
//        $result = $statement->get_result();
//        
//        $user = null;
//        
//        if($row = $result->fetch_assoc()) {
//            $user = $row['userName'];
//        }
//        
//        $this->assertEquals(
//            null,
//            $user
//        );
    }
}
?>