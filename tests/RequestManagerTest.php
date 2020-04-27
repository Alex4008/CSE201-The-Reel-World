<?php
use PHPUnit\Framework\TestCase;

// Load passwords
require_once('db.php');

final class RequestManagerTest extends TestCase
{
    private $testUsername = 'unittestuser';
    private $testUserId = null;
    private $testRequestName = 'unittestname';
    private $testRequestDes = 'unittestdescription';
    
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
        global $testUserId, $testRequestName, $testRequestDes;
        
        $this->cleanUpTest();
        $this->setUpTest();
        
        $mm = new RequestManager($GLOBALS['mysqli']);
        $mm->saveRequest($testUserId, $testRequestName, $testRequestDes);
        
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
    
    // Testing if getRequestById() returns exactly one request
    public function testCanGetRequestById(): void
    {
        $this->cleanUpTest();
        $this->setUpTest();
        $requestId = $this->addTestRequest();
        
        $mm = new RequestManager($GLOBALS['mysqli']);
        $row = $mm->getRequestById($requestId);
        
        $this->assertNotEquals(
            null,
            $row
        );
        
        $this->cleanUpTest();
    }
    
    // Testing if updateRequest() updates the request description in the database 
    public function testCanUpdateRequest(): void
    {
        $updateTestDes = "unittestdescriptionupdate";
        
        $this->cleanUpTest();
        $this->setUpTest();
        $requestId = $this->addTestRequest();
        
        $mm = new RequestManager($GLOBALS['mysqli']);
        $statement = $mm->updateRequest($requestId, $updateTestDes, 'Approved');
        
        $sql = "SELECT description FROM Requests WHERE requestId = '" . $requestId . "'";
        $sqlResult = $GLOBALS['mysqli']->query($sql);
        
        $this->assertGreaterThan(
            0,
            $sqlResult->num_rows
        );
        
        $row = $sqlResult->fetch_assoc();
        
        $this->assertEquals(
            $updateTestDes,
            $row["description"]
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
    
    // - - - - - HELPER FUNCTIONS - - - - - 
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
        global $testUserId, $testRequestName, $testRequestDes; 
        
        $sql = "INSERT INTO Requests (userId, requestName, description) VALUES ('" . $testUserId . "', '" . $testRequestName . "', '" . $testRequestDes . "')";
        
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