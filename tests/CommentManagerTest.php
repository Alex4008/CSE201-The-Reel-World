<?php
use PHPUnit\Framework\TestCase;

// Load passwords
require_once('db.php');

final class CommentManagerTest extends TestCase
{
    // Testing if CommentManager object can be created 
    public function testCanBeCreated(): void
    {
        $this->assertInstanceOf(
            CommentManager::class,
            new CommentManager($GLOBALS['mysqli'])
        );
    }
    
    // Testing if getCommentsByMovie() returns comments belonging to given movie
    // Assumes that movie titled 'Amadeus' has at least one comment 
    public function testCanGetCommentsByMovie(): void
    {
        $movieId = null;
        
        $sql = "SELECT movieId FROM Movies WHERE title = 'Amadeus'";
        if ($GLOBALS['mysqli']->query($sql) === TRUE) {
            $movieId = $GLOBALS['mysqli']->insert_id;
        }
        
        $cm = new CommentManager($GLOBALS['mysqli']);
        $statement = $cm->getCommentsByMovie($movieId);
        $result = $statement->store_result();
        
        $this->assertGreaterThan(
            0,
            $statement->num_rows
        );
    }
    
    // Testing if addComment() adds a comment to the database
    public function testCanAddComment(): void
    {      
//        $this->cleanUpTest();
//        $this->setUpTest();
//        
//        $cm = new CommentManager($GLOBALS['mysqli']);
//        $cm->addComment();
//        
//        $sql = "SELECT userId FROM Requests WHERE userId = '" . $testUserId . "'";
//        $sqlResult = $GLOBALS['mysqli']->query($sql);
//        
//        $this->assertGreaterThan(
//            0,
//            $sqlResult->num_rows
//        );
//        
//        $row = $sqlResult->fetch_assoc();
//        
//        $this->assertEquals(
//            $testUserId,
//            $row["userId"]
//        );
//        
//        $this->cleanUpTest();
    }
}
?>