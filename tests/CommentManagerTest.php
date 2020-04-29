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
    public function testCanGetCommentsByMovie(): void
    {
//        $mm = new CommentManager($GLOBALS['mysqli']);
//        $statement = $mm->getCommentsByMovie();
//        $result = $statement->store_result();
//        
//        $this->assertGreaterThan(
//            0,
//            $statement->num_rows
//        );
    }
    
    // Testing if addComment() adds a comment to the database
    public function testCanAddComment(): void
    {      
//        $this->cleanUpTest();
//        $this->setUpTest();
//        
//        $mm = new CommentManager($GLOBALS['mysqli']);
//        $mm->addComment();
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