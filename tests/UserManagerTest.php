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
}
?>