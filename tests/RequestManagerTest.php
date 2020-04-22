<?php
use PHPUnit\Framework\TestCase;

// Load passwords
require_once('db.php');

final class RequestManagerTest extends TestCase
{
    private $testUsername = 'unittestuser';
    private $testUserId = null;
    
    // Testing if RequestManager object can be created 
    public function testCanBeCreated(): void
    {
        $this->assertInstanceOf(
            RequestManager::class,
            new RequestManager($GLOBALS['mysqli'])
        );
    }
    
//    // Testing if getAllRequests() returns more than zero requests
//    // Assumes there is at least 1 request in database 
//    public function testCanGetAllRequests(): void
//    {
//        $mm = new RequestManager($GLOBALS['mysqli']);
//        $statement = $mm->getAllRequests();
//        $result = $statement->store_result();
//        
//        $this->assertGreaterThan(
//            0,
//            $statement->num_rows
//        );
//    }
    
    // Testing if saveRequest() adds a request to the database
    public function testCanSaveRequest(): void
    {        
        global $testUserId;
        
        $this->cleanUpTest();
        $this->setUpTest();
        
        $mm = new RequestManager($GLOBALS['mysqli']);
        $mm->saveRequest($testUserId, "testName", "testDescription");
        
        $sql = "SELECT userId FROM Requests WHERE userId = '" . $testUserId . "'";
        $sqlResult = $GLOBALS['mysqli']->query($sql);
        
        $this->assertGreaterThan(
            0,
            $sqlResult->num_rows
        );
        
        $row = $sqlResult->fetch_assoc();
        
        $this->assertEquals(
            $testUserId,
            $row["userId"]
        );
        
        $this->cleanUpTest();
    }
    
    // Testing if getRequests() gets all requests submitted by a given user 
    public function testCanGetRequests(): void
    {
        global $testUserId;
        
        $this->cleanUpTest();
        $this->setUpTest();
        $requestId = $this->addTestRequest();
        
        $mm = new RequestManager($GLOBALS['mysqli']);
        $statement = $mm->getRequests($testUserId);
        $result = $statement->store_result();
        
        $this->assertEquals(
            1,
            $statement->num_rows
        );
        
        $this->cleanUpTest();
    }
    
    // Testing if deleteRequest() deletes request from database
    public function testCanDeleteRequest(): void
    {            
        $this->cleanUpTest();
        $this->setUpTest();
        $requestId = $this->addTestRequest();
        
        $mm = new RequestManager($GLOBALS['mysqli']);
        $mm->deleteRequest($requestId);
        
        $sql = "SELECT requestId FROM Requests WHERE requestId = '" . $requestId . "'";
        $sqlResult = $GLOBALS['mysqli']->query($sql);
        
        $this->assertEquals(
            0,
            $sqlResult->num_rows
        );
                
        $this->cleanUpTest();
    }
    
    // Testing if getSingleRequest() returns exactly one request
    // Assumes there is a request with id 118
//    public function testCanGetSingleRequest(): void
//    {
//        $mm = new RequestManager($GLOBALS['mysqli']);
//        $statement = $mm->getSingleRequest(118);
//        $result = $statement->store_result();
//        
//        $this->assertEquals(
//            1,
//            $statement->num_rows
//        );
//    }
//    
    
    
        
    // Inserts test user
    public function setUpTest(): void 
    {
        global $testUsername, $testUserId;
        
        $sql = "INSERT INTO Users (userName, password, displayName) VALUES ('" . $testUsername . "', 'testpassword', 'Testfirst, Testlast')";
        
        if ($GLOBALS['mysqli']->query($sql) === TRUE) {
            $testUserId = $GLOBALS['mysqli']->insert_id;
        }
    }
    
    // Inserts test request 
    // Returns request id 
    public function addTestRequest(): int
    {
        global $testUserId; 
        
        $sql = "INSERT INTO Requests (userId) VALUES ('" . $testUserId . "')";
        
        if ($GLOBALS['mysqli']->query($sql) === TRUE) {
            $requestId = $GLOBALS['mysqli']->insert_id;
        }
        
        return $requestId;
    }
    
    // Cleans up test code
    public function cleanUpTest(): void 
    {
        global $testUserId; 
        
        // Delete test user's requests 
        $sql = "DELETE FROM Requests WHERE userId = '" . $testUserId . "'";
        $GLOBALS['mysqli']->query($sql);
        
        // Delete test user
        $sql = "DELETE FROM Users WHERE userId = '" . $testUserId . "'";
        $GLOBALS['mysqli']->query($sql);
    }
}
?>