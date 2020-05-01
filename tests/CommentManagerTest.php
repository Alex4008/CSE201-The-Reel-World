<?php
use PHPUnit\Framework\TestCase;

// Load passwords
require_once('db.php');

final class CommentManagerTest extends TestCase
{
    private $testCommentText = 'unittestcommenttext';
    
    // Testing if CommentManager object can be created 
    public function testCanBeCreated(): void
    {
        $this->assertInstanceOf(
            CommentManager::class,
            new CommentManager($GLOBALS['mysqli'])
        );
    }
    
    // Testing if getCommentsByMovie() returns comments belonging to given movie
    // Assumes that movie titled 'Amadeus' exists and has at least one comment 
    public function testCanGetCommentsByMovie(): void
    {
        $movieId = null;
        
        $sql = "SELECT movieId FROM Movies WHERE title = 'Amadeus'";
        $sqlResult = $GLOBALS['mysqli']->query($sql);
        $row = $sqlResult->fetch_assoc();
        $movieId = $row["movieId"];
        
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
        global $testCommentText;
        
        $this->cleanUpTest();
        
        $cm = new CommentManager($GLOBALS['mysqli']);
        $cm->addComment('1', '1', $testCommentText);
        
        $sql = "SELECT commentText FROM Comments WHERE commentText LIKE '" . $testCommentText . "'";
        $sqlResult = $GLOBALS['mysqli']->query($sql);
        
        $this->assertGreaterThan(
            0,
            $sqlResult->num_rows
        );
        
        $row = $sqlResult->fetch_assoc();
        
        $this->assertEquals(
            $testCommentText,
            $row["commentText"]
        );
        
        $this->cleanUpTest();
    }
    
    // - - - - - HELPER FUNCTIONS - - - - -     
    // Cleans up test code
    public function cleanUpTest(): void 
    {
        global $testCommentText; 
        
        // Delete test comment by commentText 
        $sql = "DELETE FROM Comments WHERE commentText LIKE '" . $testCommentText . "'";
        $GLOBALS['mysqli']->query($sql);
    }
}
?>